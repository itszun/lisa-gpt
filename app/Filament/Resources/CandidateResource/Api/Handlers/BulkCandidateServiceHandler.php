<?php
namespace App\Filament\Resources\CandidateResource\Api\Handlers;

use App\Models\Chat;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rupadana\ApiService\Http\Handlers;
// use App\Filament\Resources\BulkCandidateServiceResource;
use App\Filament\Resources\CandidateResource;

class BulkCandidateServiceHandler extends Handlers {
    public static string | null $uri = '/bulk-create';
    public static string $method = 'POST';
    public static string | null $resource = CandidateResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    public function handler(Request $request)
    {
        // $model = new (static::getModel());

        // $model->fill($request->all());

        // $model->save();

        // return static::sendSuccessResponse($model, "Successfully Create Resource");

        $validated = $request->validate([
            'created_by' => 'required|integer|exists:users,id',

            'candidates.*.talent_id' => 'required|integer|exists:talents,id',
            'candidates.*.job_opening_id' => 'required|integer|exists:job_openings,id',
            'candidates.*.regist_at' => 'required|date',

            'chat.*.session_id' => 'required|string',
            'chat.*.user_id' => 'required|integer|exists:users,id',
            'chat.*.identifier' => 'required|string',
            'chat.*.title' => 'required|string',
            'chat.*.context' => 'required|string',
            'chat.*.message' => 'required|string',
            'chat.*.response' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // ambil chat terakhir buat relasi candidates
            $lastChat = Chat::latest('id')->first();
            $userCompany = auth()->user()->id;

            $lastChat->update(['created_by' => $userCompany]);

            // bulk insert ke table candidate
            $candidatesData = collect($validated['candidates'])->map(function ($candidate) use ($validated, $lastChat, $userCompany) {
                return [
                    'talent_id'   => $candidate['talent_id'],
                    'job_opening_id' => $candidate['job_opening_id'],
                    'status'      => $candidate['status'] ?? 'draft',
                    'regist_at'      => $candidate['regist_at'] ?? now(),
                    'created_by'  => $validated['created_by'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            });
            Candidate::insert($candidatesData->toArray());

            // bulk insert ke table chat
            $chatData = collect($validated['chat'])->map(function ($chat) use ($validated) {
                return [
                    'session_id' => $chat['session_id'],
                    'user_id'    => $chat['user_id'],
                    'identifier' => $chat['identifier'],
                    'title'      => $chat['title'],
                    'context'     => $chat['context'],
                    'message'       => $chat['message'],
                    'response'       => $chat['response'],
                    'created_by' => $validated['created_by'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });
            Chat::insert($chatData->toArray());

            DB::commit();

            return static::sendSuccessResponse([
                'candidates_added' => $candidatesData->count(),
                'chats_added'      => $chatData->count(),
            ], "Successfully bulk created candidates and chats");

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
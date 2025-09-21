<?php
namespace App\Filament\Resources\CandidateResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CandidateResource;
use App\Filament\Resources\CandidateResource\Api\Requests\UpdateCandidateRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = CandidateResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Candidate
     *
     * @param UpdateCandidateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateCandidateRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();
        $model->feed();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}

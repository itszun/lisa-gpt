<?php
namespace App\Filament\Resources\CandidateResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CandidateResource;
use App\Filament\Resources\CandidateResource\Api\Requests\CreateCandidateRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CandidateResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Candidate
     *
     * @param CreateCandidateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCandidateRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
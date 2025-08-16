<?php
namespace App\Filament\Resources\JobOpeningResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobOpeningResource;
use App\Filament\Resources\JobOpeningResource\Api\Requests\UpdateJobOpeningRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = JobOpeningResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update JobOpening
     *
     * @param UpdateJobOpeningRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateJobOpeningRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}
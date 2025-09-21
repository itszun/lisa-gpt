<?php
namespace App\Filament\Resources\JobOpeningResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobOpeningResource;
use App\Filament\Resources\JobOpeningResource\Api\Requests\CreateJobOpeningRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = JobOpeningResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create JobOpening
     *
     * @param CreateJobOpeningRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateJobOpeningRequest $request)
    {
        $model = new (static::getModel());

        $model->status = 1;
        $model->fill($request->all());
        if(empty($model->status)) {
            $model->status = 1;
        }

        $model->save();
        $model->feed();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}

<?php
namespace App\Filament\Resources\CompanyPropertyResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CompanyPropertyResource;
use App\Filament\Resources\CompanyPropertyResource\Api\Requests\CreateCompanyPropertyRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CompanyPropertyResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create CompanyProperty
     *
     * @param CreateCompanyPropertyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCompanyPropertyRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
<?php
namespace App\Filament\Resources\CompanyResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\CompanyResource\Api\Requests\CreateCompanyRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = CompanyResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Company
     *
     * @param CreateCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateCompanyRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
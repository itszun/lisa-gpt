<?php
namespace App\Filament\Resources\CompanyResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\CompanyResource\Api\Requests\UpdateCompanyRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = CompanyResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update Company
     *
     * @param UpdateCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateCompanyRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}
<?php
namespace App\Filament\Resources\JobOpeningResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\JobOpeningResource;
use App\Filament\Resources\JobOpeningResource\Api\Requests\EvaluateOpeningRequest;
use Spatie\QueryBuilder\QueryBuilder;

class EvaluateHandler extends Handlers {
    public static string | null $uri = '/{id}/evaluate';
    public static string | null $resource = JobOpeningResource::class;

    public static function getMethod()
    {
        return Handlers::GET;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create JobOpening
     *
     * @param EvaluateOpeningRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        $model = $query;

        $result = $model->evaluateOpeningProcess();


        return static::sendSuccessResponse($result, "Evaluate Progress status:".$result['status']);
    }
}

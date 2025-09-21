<?php
namespace App\Filament\Resources\JobOpeningResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\JobOpeningResource;
use Illuminate\Routing\Router;


class JobOpeningApiService extends ApiService
{
    protected static string | null $resource = JobOpeningResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
            Handlers\EvaluateHandler::class,
        ];

    }
}

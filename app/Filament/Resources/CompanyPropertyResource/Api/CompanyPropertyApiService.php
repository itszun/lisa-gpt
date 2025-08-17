<?php
namespace App\Filament\Resources\CompanyPropertyResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CompanyPropertyResource;
use Illuminate\Routing\Router;


class CompanyPropertyApiService extends ApiService
{
    protected static string | null $resource = CompanyPropertyResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}

<?php
namespace App\Filament\Resources\CompanyResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CompanyResource;
use Illuminate\Routing\Router;


class CompanyApiService extends ApiService
{
    protected static string | null $resource = CompanyResource::class;

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

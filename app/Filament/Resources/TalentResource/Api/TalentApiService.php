<?php
namespace App\Filament\Resources\TalentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TalentResource;
use Illuminate\Routing\Router;


class TalentApiService extends ApiService
{
    protected static string | null $resource = TalentResource::class;

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

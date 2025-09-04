<?php
namespace App\Filament\Resources\ChatResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ChatResource;
use Illuminate\Routing\Router;


class ChatApiService extends ApiService
{
    protected static string | null $resource = ChatResource::class;

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

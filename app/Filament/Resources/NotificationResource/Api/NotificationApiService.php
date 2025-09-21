<?php

namespace App\Filament\Resources\NotificationResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\NotificationResource;
use Illuminate\Routing\Router;

class NotificationApiService extends ApiService
{
    protected static string | null $resource = NotificationResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\PushHandler::class
        ];
    }
}

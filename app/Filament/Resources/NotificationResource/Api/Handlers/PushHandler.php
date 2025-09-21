<?php

namespace App\Filament\Resources\NotificationResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\NotificationResource;
use App\Filament\Resources\NotificationResource\Api\Requests\PushNotificationRequest;
use App\Models\User;
use Filament\Notifications\Notification;

class PushHandler extends Handlers
{
    public static string | null $uri = '/push';
    public static string | null $resource = NotificationResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel()
    {
        return static::$resource::getModel();
    }

    public function handler(PushNotificationRequest $request)
    {
        $chat_user_id = $request->get('chat_user_id');
        $user = User::where('chat_user_id', $chat_user_id)
            ->firstOr(function ($chat_user_id) {
                static::sendNotFoundResponse($chat_user_id . " Not Found!");
            });

        $user->notify(
            Notification::make()
                ->title($request->get('subject'))
                ->body($request->get('body'))
                ->toDatabase()
        );

        return static::sendSuccessResponse("Push Notification created");
    }
}

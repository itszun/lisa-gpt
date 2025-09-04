<?php
namespace App\Filament\Resources\ChatResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ChatResource;
use App\Filament\Resources\ChatResource\Api\Requests\CreateChatRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ChatResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Chat
     *
     * @param CreateChatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateChatRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}
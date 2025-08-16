<?php

namespace App\Filament\Resources\JobOpeningResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\JobOpeningResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\JobOpeningResource\Api\Transformers\JobOpeningTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = JobOpeningResource::class;


    /**
     * Show JobOpening
     *
     * @param Request $request
     * @return JobOpeningTransformer
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

        return new JobOpeningTransformer($query);
    }
}

<?php

namespace App\Filament\Resources\CompanyPropertyResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\CompanyPropertyResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\CompanyPropertyResource\Api\Transformers\CompanyPropertyTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = CompanyPropertyResource::class;


    /**
     * Show CompanyProperty
     *
     * @param Request $request
     * @return CompanyPropertyTransformer
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

        return new CompanyPropertyTransformer($query);
    }
}

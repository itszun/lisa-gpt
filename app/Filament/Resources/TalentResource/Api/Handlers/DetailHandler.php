<?php

namespace App\Filament\Resources\TalentResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\TalentResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\TalentResource\Api\Transformers\TalentTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = TalentResource::class;


    /**
     * Show Talent
     *
     * @param Request $request
     * @return TalentTransformer
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

        return new TalentTransformer($query);
    }
}

<?php

namespace App\Filament\Resources\CandidateResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\CandidateResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\CandidateResource\Api\Transformers\CandidateTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = CandidateResource::class;


    /**
     * Show Candidate
     *
     * @param Request $request
     * @return CandidateTransformer
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

        return new CandidateTransformer($query);
    }
}

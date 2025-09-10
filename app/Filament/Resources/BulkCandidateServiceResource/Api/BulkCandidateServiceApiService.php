<?php

namespace App\Filament\Resources\BulkCandidateServiceResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\BulkCandidateServiceResource;
use Illuminate\Routing\Router;

class BulkCandidateServiceApiService extends ApiService
{
    protected static string | null $resource = BulkCandidateServiceResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\BulkCandidateServiceHandler::class
        ];
    }
}

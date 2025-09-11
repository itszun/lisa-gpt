<?php
namespace App\Filament\Resources\CandidateResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\CandidateResource;
use Illuminate\Routing\Router;


class CandidateApiService extends ApiService
{
    protected static string | null $resource = CandidateResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
            Handlers\BulkCandidateServiceHandler::class
        ];

    }
}

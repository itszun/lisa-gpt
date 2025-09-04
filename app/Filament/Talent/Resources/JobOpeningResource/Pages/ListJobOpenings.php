<?php

namespace App\Filament\Talent\Resources\JobOpeningResource\Pages;

use App\Filament\Talent\Resources\JobOpeningResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobOpenings extends ListRecords
{
    protected static string $resource = JobOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}

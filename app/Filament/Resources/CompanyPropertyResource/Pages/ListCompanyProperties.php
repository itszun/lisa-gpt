<?php

namespace App\Filament\Resources\CompanyPropertyResource\Pages;

use App\Filament\Resources\CompanyPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompanyProperties extends ListRecords
{
    protected static string $resource = CompanyPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

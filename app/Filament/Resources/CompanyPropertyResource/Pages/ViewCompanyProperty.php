<?php

namespace App\Filament\Resources\CompanyPropertyResource\Pages;

use App\Filament\Resources\CompanyPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCompanyProperty extends ViewRecord
{
    protected static string $resource = CompanyPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

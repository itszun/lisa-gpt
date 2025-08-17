<?php

namespace App\Filament\Resources\CompanyPropertyResource\Pages;

use App\Filament\Resources\CompanyPropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanyProperty extends EditRecord
{
    protected static string $resource = CompanyPropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

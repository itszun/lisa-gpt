<?php

namespace App\Filament\Resources\JobOpeningResource\Pages;

use App\Filament\Resources\JobOpeningResource;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\Talent;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewJobOpening extends ViewRecord
{
    protected static string $resource = JobOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
    return $infolist
        ->schema([
            Fieldset::make('Job Details')
                ->schema([
                    TextEntry::make('company.name')
                        ->label('Company Name'),

                    TextEntry::make('title')
                        ->label('Job Title')
                        ->size(TextEntry\TextEntrySize::Large),

                    TextEntry::make('body')
                        ->label('Job Description'),

                    TextEntry::make('due_date')
                        ->label('Due Date')
                        ->date('d M Y')
                        ->badge()
                        ->color('warning'), // Bisa jadi pengingat

                    TextEntry::make('status')
                        ->label('Status')
                        ->formatStateUsing(fn (int $state): string => match ($state) {
                            1 => 'Open',
                            2 => 'Closed',
                            3 => 'Canceled',
                            default => 'Unknown',
                        })
                        ->color(fn (int $state): string => match ($state) {
                            1 => 'success',
                            2 => 'danger',
                            3 => 'gray',
                            default => 'warning',
                        }),
                ]),

                Tabs::make('Candidate')
                    ->tabs([
                        Tab::make('Candidate')
                            ->schema([
                    TableRepeatableEntry::make('candidates')
                        ->schema([
                            TextEntry::make('id')
                                ->label('ID')
                                ->size(TextEntry\TextEntrySize::Large),
                            TextEntry::make('talent.name')
                                ->url(fn(Candidate $candidate) => route('filament.admin.resources.talent.view', $candidate->talent_id))
                                ->label('Nama Kandidat')
                                ->size(TextEntry\TextEntrySize::Large),
                            TextEntry::make('talent.position')
                                ->label('Position'),
                            TextEntry::make('status')
                                ->label('Status'),
                        ])
                                ->columnSpan(2),
                            ]),
                    ])
        ])
            ->columns(1);
        ;
    }
}

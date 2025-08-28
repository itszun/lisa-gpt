<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CompanyResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationLabel = 'Company';

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->default(1),
                Forms\Components\Repeater::make('company_properties')
                    ->relationship('properties')
                    ->label('Clause')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Key')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->addActionLabel('Add Clause'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TagsColumn::make('status')
                    ->getStateUsing(fn ($record) => $record->status == 1 ? ['Active'] : ['Inactive'])
                    ->colors([
                        'success' => fn ($state) => in_array('Active', $state),
                        'danger' => fn ($state) => in_array('Inactive', $state),
                    ])
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Tabs
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Company Detail')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('description'),
                ])
                ->columns(2),

                Tabs::make('Company')
                    ->tabs([
                        Tab::make('Job Openings')
                            ->schema([
                                TableRepeatableEntry::make('jobOpenings')
                                ->schema([
                                    TextEntry::make('title'),
                                    TextEntry::make('body')
                                        ->limit(50),
                                    TextEntry::make('due_date')
                                        ->date('d-M-Y'),
                                    TextEntry::make('status')
                                        ->getStateUsing(fn ($record) => $record->status == 1 ? 'Active' : 'Inactive')
                                        ->badge()
                                        ->colors([
                                            'success' => fn ($state) => $state == 1,
                                            'danger'  => fn ($state) => $state == 0,
                                        ]),
                                    TextEntry::make('id')
                                        ->label('Detail')
                                        ->url(fn ($record) => route('filament.admin.resources.job-openings.view', $record->id))
                                        // ->openUrlInNewTab()
                                        ->formatStateUsing(fn () => 'View'),
                                ])
                                ->columnSpan(2),
                            ]),
                        Tab::make('Candidate')
                            ->schema([
                                TableRepeatableEntry::make('candidates')
                                ->schema([
                                    TextEntry::make('talent.name')
                                        ->label('Talent'),
                                    TextEntry::make('jobOpening.title')
                                        ->label('Job Opening'),
                                    TextEntry::make('regist_at')
                                        ->date('d-M-Y'),
                                    TextEntry::make('interview_schedule')
                                        ->date('d-M-Y'),
                                    TextEntry::make('notified_at')
                                        ->date('d-M-Y'),
                                    TextEntry::make('status')
                                        ->getStateUsing(fn ($record) => $record->status == 1 ? 'Active' : 'Inactive')
                                        ->badge()
                                        ->colors([
                                            'success' => fn ($state) => $state == 1,
                                            'danger'  => fn ($state) => $state == 0,
                                        ]),
                                    TextEntry::make('id')
                                        ->label('Detail')
                                        ->url(fn ($record) => route('filament.admin.resources.candidates.view', $record->id))
                                        // ->openUrlInNewTab()
                                        ->formatStateUsing(fn () => 'View'),
                                ])
                                ->columnSpan(2),
                            ]),
                        Tab::make('Company Properties')
                            ->schema([
                                TableRepeatableEntry::make('properties')
                                ->schema([
                                    TextEntry::make('key'),
                                    TextEntry::make('value'),
                                ])
                                ->columnSpan(2),
                            ])
                            ->columns(1),

                    ])
            ])
            ->columns(1);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view' => Pages\ViewCompany::route('/{record}'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}

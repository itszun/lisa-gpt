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
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CompanyResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;

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
                Tabs::make('Company')
                    ->tabs([
                        Tab::make('Job Openings')
                            ->schema([
                                // TableEntry::make('jobOpenings')
                                // ->label('Job Openings')
                                // ->columns([
                                //     TextEntry::make('title')->label('Title'),
                                //     TextEntry::make('body')->label('Body'),
                                //     DateEntry::make('due_date')->label('Due Date'),
                                //     BadgeEntry::make('status')
                                //         ->label('Status')
                                //         ->colors([
                                //             '1' => 'Active',
                                //             '0' => 'Inactive',
                                //         ]),
                                // ]),
                            ]),
                        Tab::make('Candidate')
                            ->schema([
                                RepeatableEntry::make('candidates')
                                    ->label('Candidates')
                                    ->schema([
                                        TextEntry::make('name')->label('Name'),
                                        TextEntry::make('email')->label('Email'),
                                    ]),
                            ]),
                        Tab::make('Company Properties')
                            ->schema([
                                RepeatableEntry::make('properties')
                                    ->label('Properties')
                                    ->schema([
                                        TextEntry::make('key')->label('Key'),
                                        TextEntry::make('value')->label('Value'),
                                    ])
                                    ->columns(1)
                                    ->grid(2),
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

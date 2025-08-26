<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Talent;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TalentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TalentResource\RelationManagers;

class TalentResource extends Resource
{
    protected static ?string $model = Talent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birthdate')
                    ->required(),
                Forms\Components\Textarea::make('summary')
                    ->required(),
                Forms\Components\TagsInput::make('skills')
                    ->required(),
                Forms\Components\TagsInput::make('educations')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('summary')
                    ->limit(50),
                Tables\Columns\TagsColumn::make('skills')
                    ->sortable(),
                Tables\Columns\TagsColumn::make('educations')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('birthdate')
                    ->form([
                        Forms\Components\DatePicker::make('birthdate_from')
                            ->label('Birthdate From'),
                        Forms\Components\DatePicker::make('birthdate_until')
                            ->label('Birthdate Until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['birthdate_from'], fn ($q, $date) => $q->whereDate('birthdate', '>=', $date))
                            ->when($data['birthdate_until'], fn ($q, $date) => $q->whereDate('birthdate', '<=', $date));
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalent::route('/'),
            'create' => Pages\CreateTalent::route('/create'),
            'view' => Pages\ViewTalent::route('/{record}'),
            'edit' => Pages\EditTalent::route('/{record}/edit'),
        ];
    }
}

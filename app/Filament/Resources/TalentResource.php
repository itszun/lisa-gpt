<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Talent;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\TalentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TalentResource\RelationManagers;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

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
                Forms\Components\Select::make('status')
                    ->options([
                        1 => 'Draft',
                        2 => 'Reviewed',
                        100 => 'Listed',
                        200 => 'Unlisted',
                        300 => 'Blacklisted',
                    ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->talent())
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Talent Detail')
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('position'),
                    TextEntry::make('birthdate')->date('d-M-Y'),
                    TextEntry::make('summary'),
                    TextEntry::make('skills'),
                    TextEntry::make('educations'),
                    TextEntry::make('status')
                        ->getStateUsing(fn ($record) => $record->status = match ($record->status) {
                            1 => 'Draft',
                            2 => 'Reviewed',
                            100 => 'Listed',
                            200 => 'Unlisted',
                            300 => 'Blacklisted',
                            default => 'Unknown',
                        })
                        ->badge(),
                ])
                ->columns(2),

                Tabs::make('Candidate')
                    ->tabs([
                        Tab::make('Candidate')
                            ->schema([
                                TableRepeatableEntry::make('candidates')
                                ->schema([
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
                        Tab::make('Companies')
                            ->schema([
                                TableRepeatableEntry::make('companies')
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Company Name'),
                                    TextEntry::make('description')
                                        ->label('Company Description')
                                        ->limit(50),
                                ])
                                ->columnSpan(2),
                            ]),
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
            'index' => Pages\ListTalent::route('/'),
            'create' => Pages\CreateTalent::route('/create'),
            'view' => Pages\ViewTalent::route('/{record}'),
            'edit' => Pages\EditTalent::route('/{record}/edit'),
        ];
    }
}

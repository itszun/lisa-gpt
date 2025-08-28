<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Candidate;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\CandidateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CandidateResource\RelationManagers;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('talent_id')
                    ->relationship('talent', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('job_opening_id')
                    ->relationship('jobOpening', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Inactive',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('regist_at')
                    ->visibleOn('edit')
                    ->required(),
                Forms\Components\DateTimePicker::make('interview_schedule')
                    ->visibleOn('edit')
                    ->required(),
                Forms\Components\DateTimePicker::make('notified_at')
                    ->visibleOn('edit')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('talent.name')
                    ->label('Talent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobOpening.title')
                    ->label('Job Opening')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TagsColumn::make('status')
                    ->getStateUsing(fn ($record) => $record->status == 1 ? ['Active'] : ['Inactive'])
                    ->colors([
                        'success' => fn ($state) => in_array('Active', $state),
                        'danger' => fn ($state) => in_array('Inactive', $state),
                    ])
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('regist_at')
                    ->date(fn ($record) => $record->regist_at ? 'd-m-Y H:i' : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('interview_schedule')
                    ->date(fn ($record) => $record->interview_schedule ? 'd-m-Y H:i' : null)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notified_at')
                    ->date(fn ($record) => $record->notified_at ? 'd-m-Y H:i' : null)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('talent_id')
                    ->relationship('talent', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('job_opening_id')
                    ->relationship('jobOpening', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Inactive',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('regist_at')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DatePicker::make('from')
                                    ->label('Registered From'),
                                Forms\Components\DatePicker::make('to')
                                    ->label('Registered To'),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('regist_at', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('regist_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('interview_schedule')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DatePicker::make('from')
                                    ->label('Interview Schedule From'),
                                Forms\Components\DatePicker::make('to')
                                    ->label('Interview Schedule To'),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('interview_schedule', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('interview_schedule', '<=', $date));
                    }),
                Tables\Filters\Filter::make('notified_at')
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\DatePicker::make('from')
                                    ->label('Notified From'),
                                Forms\Components\DatePicker::make('to')
                                    ->label('Notified To'),
                            ])
                            ->columns(2),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('notified_at', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('notified_at', '<=', $date));
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
                Grid::make(2)
                    ->schema([
                        Section::make('Talent Info')
                            ->schema([
                                TextEntry::make('talent.name')
                                    ->label('Name')
                                    ->url(fn ($record) => route('filament.admin.resources.talent.view', $record->talent_id))
                                    ->color('primary'),
                                TextEntry::make('jobOpening.title')
                                    ->label('Job Opening')
                                    ->url(fn ($record) => route('filament.admin.resources.job-openings.view', $record->job_opening_id))
                                    ->color('primary'),
                                TextEntry::make('talent.birthdate')
                                    ->date('d M Y'),
                                TextEntry::make('talent.summary')
                                    ->label('Summary'),
                            ])
                            ->columns(2),

                        Section::make('Additional Info')
                            ->schema([
                                TextEntry::make('regist_at')
                                    ->dateTime()
                                    ->label('Registered At')
                                    ->hidden(fn ($record) => blank($record->regist_at)),

                                TextEntry::make('interview_schedule')
                                    ->dateTime()
                                    ->label('Interview Schedule')
                                    ->hidden(fn ($record) => blank($record->interview_schedule)),

                                    TextEntry::make('notified_at')
                                    ->dateTime()
                                    ->label('Notified At')
                                    ->hidden(fn ($record) => blank($record->notified_at)),
                            ])
                            ->columns(3),
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
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'view' => Pages\ViewCandidate::route('/{record}'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}

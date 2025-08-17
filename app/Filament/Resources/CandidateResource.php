<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CandidateResource\Pages;
use App\Filament\Resources\CandidateResource\RelationManagers;
use App\Models\Candidate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->default(fn ($record) => $record->regist_at ? $record->regist_at->format('d-m-Y H:i') : '-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('interview_schedule')
                    ->default(fn ($record) => $record->interview_schedule ? $record->interview_schedule->format('d-m-Y H:i') : '-')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notified_at')
                    ->default(fn ($record) => $record->notified_at ? $record->notified_at->format('d-m-Y H:i') : '-')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'Active',
                        2 => 'Inactive',
                    ])
                    ->label('Status'),
                Tables\Filters\Filter::make('regist_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Registered From'),
                        Forms\Components\DatePicker::make('to')
                            ->label('Registered To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('regist_at', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('regist_at', '<=', $date));
                    }),
                Tables\Filters\Filter::make('interview_schedule')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Interview Schedule From'),
                        Forms\Components\DatePicker::make('to')
                            ->label('Interview Schedule To'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('interview_schedule', '>=', $date))
                            ->when($data['to'], fn ($q, $date) => $q->whereDate('interview_schedule', '<=', $date));
                    }),
                Tables\Filters\Filter::make('notified_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Notified From'),
                        Forms\Components\DatePicker::make('to')
                            ->label('Notified To'),
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

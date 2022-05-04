<?php

namespace App\Filament\Resources;

use App\Models\ScheduleUnavailability;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\ScheduleUnavailabilityResource\Pages;

class ScheduleUnavailabilityResource extends Resource
{
    protected static ?string $model = ScheduleUnavailability::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'id';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                Time::make('start_time')
                    ->rules(['required', 'date_format:H:i:s'])
                    ->placeholder('Start Time')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                Time::make('end_time')
                    ->rules(['required', 'date_format:H:i:s'])
                    ->placeholder('End Time')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('schedule_id')
                    ->rules(['required', 'exists:schedules,id'])
                    ->relationship('schedule', 'date')
                    ->searchable()
                    ->placeholder('Schedule')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns::make('start_time'),
                Tables\Columns::make('end_time'),
                Tables\Columns\TextColumn::make('schedule.date')->limit(50),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '>=',
                                    $date
                                )
                            )
                            ->when(
                                $data['created_until'],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    'created_at',
                                    '<=',
                                    $date
                                )
                            );
                    }),

                MultiSelectFilter::make('schedule_id')->relationship(
                    'schedule',
                    'date'
                ),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScheduleUnavailabilities::route('/'),
            'create' => Pages\CreateScheduleUnavailability::route('/create'),
            'edit' => Pages\EditScheduleUnavailability::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class ScheduleUnavailabilitiesRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'scheduleUnavailabilities';

    protected static ?string $recordTitleAttribute = 'id';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Bloqueio';

    protected static ?string $title = 'Bloqueios de Agenda';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TimePicker::make('start_time')
                    ->rules(['required', 'date_format:H:i'])
                    ->withoutSeconds()
                    ->placeholder('Start Time')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TimePicker::make('end_time')
                    ->rules(['required', 'date_format:H:i'])
                    ->withoutSeconds()
                    ->placeholder('End Time')
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
                Tables\Columns\TextColumn::make('start_time')->label('Inicio')->date('H:i'),
                Tables\Columns\TextColumn::make('end_time')->label('Fim')->date('H:i'),
//                Tables\Columns\TextColumn::make('schedule.date')->limit(50),
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
}

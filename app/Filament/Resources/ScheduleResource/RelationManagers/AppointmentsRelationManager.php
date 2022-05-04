<?php

namespace App\Filament\Resources\ScheduleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TimePicker;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Illuminate\Support\Facades\Auth;

class AppointmentsRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'appointments';

    protected static ?string $recordTitleAttribute = 'token';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Agendamentos';

    protected static ?string $label = 'Agendamento';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TimePicker::make('start_time')
                    ->rules(['required', 'date_format:H:i:s'])
                    ->withoutSeconds()
                    ->placeholder('Start Time')
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),

                TimePicker::make('end_time')
                    ->rules(['required', 'date_format:H:i:s'])
                    ->withoutSeconds()
                    ->placeholder('End Time')
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),


                BelongsToSelect::make('user_id')
                    ->rules(['required', 'exists:users,id'])
                    ->relationship('user', 'name')
                    ->default(Auth::id())
                    ->disabled(! auth()->user()->isSuperAdmin())
//                    ->searchable()
                    ->placeholder('User')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('patient_id')
                    ->rules(['required', 'exists:patients,id'])
                    ->relationship('patient', 'name')
//                    ->searchable()
                    ->placeholder('Patient')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('service_id')
                    ->rules(['required', 'exists:services,id'])
                    ->relationship('service', 'name')
//                    ->searchable()
                    ->placeholder('Service')
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
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('uuid')->limit(50),
                Tables\Columns\TextColumn::make('token')->limit(50),
                Tables\Columns\TextColumn::make('cancelled_at')->date(),
                Tables\Columns\TextColumn::make('user.name')->limit(50),
                Tables\Columns\TextColumn::make('patient.name')->limit(50),
                Tables\Columns\TextColumn::make('schedule.date')->limit(50),
                Tables\Columns\TextColumn::make('service.name')->limit(50),
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

                MultiSelectFilter::make('user_id')->relationship(
                    'user',
                    'name'
                ),

                MultiSelectFilter::make('patient_id')->relationship(
                    'patient',
                    'name'
                ),

                MultiSelectFilter::make('schedule_id')->relationship(
                    'schedule',
                    'date'
                ),

                MultiSelectFilter::make('service_id')->relationship(
                    'service',
                    'name'
                ),
            ]);
    }
}

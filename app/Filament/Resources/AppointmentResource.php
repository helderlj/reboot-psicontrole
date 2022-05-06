<?php

namespace App\Filament\Resources;

use App\Models\Appointment;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\AppointmentResource\Pages;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'token';

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

                TextInput::make('uuid')
                    ->rules(['required', 'max:255'])
                    ->placeholder('Uuid')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('token')
                    ->rules(['required', 'max:255', 'string'])
                    ->placeholder('Token')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                DatePicker::make('cancelled_at')
                    ->rules(['nullable', 'date'])
                    ->placeholder('Cancelled At')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('patient_id')
                    ->rules(['required', 'exists:patients,id'])
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->placeholder('Patient')
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

                BelongsToSelect::make('service_id')
                    ->rules(['required', 'exists:services,id'])
                    ->relationship('service', 'name')
                    ->searchable()
                    ->placeholder('Service')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('user_id')
                    ->rules(['required', 'exists:users,id'])
                    ->relationship('user', 'name')
                    ->searchable()
                    ->placeholder('User')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                DatePicker::make('date')
                    ->rules(['required', 'date'])
                    ->placeholder('Date')
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
                Tables\Columns\TextColumn::make('uuid')->limit(50),
                Tables\Columns\TextColumn::make('token')->limit(50),
                Tables\Columns\TextColumn::make('cancelled_at')->date(),
                Tables\Columns\TextColumn::make('patient.name')->limit(50),
                Tables\Columns\TextColumn::make('schedule.date')->limit(50),
                Tables\Columns\TextColumn::make('service.name')->limit(50),
                Tables\Columns\TextColumn::make('user.name')->limit(50),
                Tables\Columns\TextColumn::make('date')->date(),
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

                MultiSelectFilter::make('user_id')->relationship(
                    'user',
                    'name'
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\ScheduleResource\RelationManagers;

use App\Forms\Components\AvailabilitySlotPicker;
use App\Models\Service;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ViewField;
use Filament\Tables;
use Filament\Resources\{Form, Table};
use Filament\Forms\Components\Grid;
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

                BelongsToSelect::make('service_id')
                    ->label('Serviço')
                    ->rules(['required', 'exists:services,id'])
                    ->relationship('service', 'name')
                    ->placeholder('Selecione')
                    ->reactive()
//                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
//                        $serviceDuration = Service::find($state)->duration;
//                        $inicio = $get('start_time');
//                        $set('end_time', ((Carbon::createFromTimestamp($inicio))->addMinute($serviceDuration))->format('h:i') );
//                        $set('selected_service_id', $state);
//                    })
                    ->columnSpan([
                        'default' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ]),

                Hidden::make('end_time'),


                BelongsToSelect::make('patient_id')
                    ->label('Paciente')
                    ->rules(['required', 'exists:patients,id'])
                    ->relationship('patient', 'name')
                    ->placeholder('Selecione')
                    ->columnSpan([
                        'default' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ]),

                Hidden::make('selected_service_id'),

                AvailabilitySlotPicker::make('start_time')
                    ->view('forms.components.availability-slot-picker')
                    ->boot()
                    ->reactive()
                    ->label('Horarios Disponiveis')
                    ->serviceId(fn(callable $get) => $get('service_id'))
                    ->scheduleId(fn($livewire): string => $livewire->ownerRecord->id)
                    ->userId(fn($livewire): string => $livewire->ownerRecord->user_id)
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
                Tables\Columns\TextColumn::make('start_time')->date('H:i')->label('Hora Inicio'),
                Tables\Columns\TextColumn::make('end_time')->date('H:i')->label('Hora Fim'),
                Tables\Columns\TextColumn::make('user.name')->limit(50)->label('Terapeuta'),
                Tables\Columns\TextColumn::make('patient.name')->limit(50)->label('Paciente'),
                Tables\Columns\TextColumn::make('service.name')->limit(50)->label('Serviço Agendado'),
                Tables\Columns\TextColumn::make('service.duration')->limit(50)->label('Duração (minutos)'),
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

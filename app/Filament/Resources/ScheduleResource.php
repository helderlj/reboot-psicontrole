<?php

namespace App\Filament\Resources;

use App\Models\Schedule;
use Filament\{Forms\Components\TimePicker, Forms\Components\ViewField, Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\ScheduleResource\Pages;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

//    protected static ?string $recordTitleAttribute = 'date';

    protected static ?string $label = 'Agenda';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([

                BelongsToSelect::make('user_id')
                    ->label('Terapeuta')
                    ->rules(['required', 'exists:users,id'])
                    ->relationship('user', 'name')
                    ->placeholder('Terapeuta')
                    ->default(Auth::id())
                    ->disabled(! auth()->user()->isSuperAdmin())
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                DatePicker::make('date')
                    ->label('Data')
                    ->rules(['required', 'date'])
                    ->placeholder('Data')
                    ->displayFormat('d M, Y')
                    ->minDate(now())
                    ->default(now())
                    ->columnSpan([
                        'default' => 4,
                        'md' => 4,
                        'lg' => 4,
                    ]),

                TimePicker::make('start_time')
                    ->label('Inicio')
                    ->rules(['required', 'date_format:H:i'])
                    ->placeholder('Hora Inicio')
                    ->withoutSeconds()
                    ->format('H:i')
                    ->default('08:00')
                    ->columnSpan([
                        'default' => 4,
                        'md' => 4,
                        'lg' => 4,
                    ]),

                TimePicker::make('end_time')
                    ->label('Fim')
                    ->rules(['required', 'date_format:H:i'])
                    ->placeholder('Hora Inicio')
                    ->withoutSeconds()
                    ->default('18:00')
                    ->columnSpan([
                        'default' => 4,
                        'md' => 4,
                        'lg' => 4,
                    ]),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Terapeuta')->limit(50),
                Tables\Columns\TextColumn::make('date')->label('Data')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->label('Inicio')->dateTime('H:i'),
                Tables\Columns\TextColumn::make('end_time')->label('Fim')->dateTime('H:i'),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ScheduleResource\RelationManagers\AppointmentsRelationManager::class,
            ScheduleResource\RelationManagers\ScheduleUnavailabilitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}

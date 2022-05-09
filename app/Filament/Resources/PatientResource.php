<?php

namespace App\Filament\Resources;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Filament\{Tables, Forms};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\PatientResource\Pages;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-add';

    protected static ?string $label = 'Paciente';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->rules(['required', 'max:255', 'string'])
                    ->placeholder('Name')
                    ->columnSpan([
                        'default' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ]),

                TextInput::make('email')
                    ->rules(['required', 'email'])
                    ->email()
                    ->placeholder('Email')
                    ->columnSpan([
                        'default' => 6,
                        'md' => 6,
                        'lg' => 6,
                    ]),

                TextInput::make('phone')
                    ->label('Telefone')
                    ->rules(['required', 'max:255', 'string'])
                    ->placeholder('(00)00000-0000')
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(00)00000-0000'))
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),

                TextInput::make('phone_alt')
                    ->label('Telefone Alternativo')
                    ->rules(['nullable', 'max:255', 'string'])
                    ->placeholder('(00)00000-0000')
                    ->mask(fn (TextInput\Mask $mask) => $mask->pattern('(00)00000-0000'))
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),

                DatePicker::make('birthday')
                    ->label('Aniversário')
                    ->rules(['required', 'date'])
                    ->placeholder('Birthday')
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),

                DatePicker::make('starting_date')
                    ->label('Data de Inicio')
                    ->rules(['required', 'date'])
                    ->placeholder('Starting Date')
                    ->columnSpan([
                        'default' => 3,
                        'md' => 3,
                        'lg' => 3,
                    ]),

                Toggle::make('is_active')
                    ->label('Ativo')
                    ->rules(['required', 'boolean'])
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                RichEditor::make('summary')
                    ->label('Demanda Inicial')
                    ->rules(['nullable', 'max:2048'])
                    ->placeholder('Summary')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                BelongsToSelect::make('user_id')
                    ->label('Terapeuta')
                    ->rules(['required', 'exists:users,id'])
                    ->relationship('user', 'name')
                    ->default(Auth::id())
                    ->disabled(! auth()->user()->isSuperAdmin())
                    ->placeholder('User')
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
                Tables\Columns\TextColumn::make('name')->label('Nome')->limit(50)->sortable(),
//                Tables\Columns\TextColumn::make('email')->limit(50),
                Tables\Columns\TextColumn::make('phone')->label('Tel.')->limit(50),
//                Tables\Columns\TextColumn::make('birthday')->label('Data de Nasc.')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('starting_date')->label('Data de Inicio')->date('d/m/Y')->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')->label('Ativo')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->limit(50)->label('Terapeuta')->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}

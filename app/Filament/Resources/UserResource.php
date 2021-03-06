<?php

namespace App\Filament\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Filament\{Forms\Components\FileUpload, Tables, Forms, Tables\Actions\BulkAction, Tables\Actions\ButtonAction};
use Filament\Resources\{Form, Table, Resource};
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Maatwebsite\Excel\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Usuários / Terapeutas';

    protected static ?string $navigationGroup = 'Root';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(['default' => 0])->schema([
                TextInput::make('name')
                    ->rules(['required', 'max:255', 'string'])
                    ->placeholder('Name')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('email')
                    ->rules(['required', 'email'])
                    ->unique(ignorable: fn(?Model $record): ?Model => $record)
                    ->email()
                    ->placeholder('Email')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),

                TextInput::make('password')
                    ->rules(['required'])
                    ->password()
                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))
                    ->placeholder('Password')
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
                Tables\Columns\TextColumn::make('name')->limit(50),
                Tables\Columns\TextColumn::make('email')->limit(50),
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
            ])
            ->bulkActions([
                ExportAction::make('export')
                    ->label('Exportar Selecionados')
                    ->icon('heroicon-o-download')
                    ->color('success')
                    ->withHeadings()
                    ->withWriterType(Excel::CSV),
                BulkAction::make('delete')
                    ->label('Deletar Selecionados')
                    ->action(fn(Collection $records) => $records->each->delete())
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-trash'),
            ])
            ->headerActions([
                ButtonAction::make('create')
                    ->form([
                        FileUpload::make("name")
                            ->label("Arquivo CSV")
                            ->required(),
                        Forms\Components\Toggle::make('header')
                            ->label("Tem cabeçalho")
                    ])
                    ->label("Importar CSV")
                    ->action(function ($data) {
                        $users = [];
                        if (($open = fopen(asset('storage') . "/" . $data['name'], "r")) !== FALSE) {
                            while (($rows = fgetcsv($open, 1000, ",")) !== FALSE) {
                                $users[] = $rows;
                            }
                            fclose($open);
                        }
                        for ($i = 0; $i < count($users); $i++) {
                            if ($data['header']) {
                                if($i == 0) {
                                    $i++;
                                }
                            }
                            User::updateOrCreate([
                                'name' => $users[$i][0],
                                'email' => $users[$i][1],
                                'password' => Hash::make('password')
                            ]);
                        }
                    })
            ]);;
    }

    public static function getRelations(): array
    {
        return [UserResource\RelationManagers\ServicesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

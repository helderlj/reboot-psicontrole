<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use App\Models\Patient;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class UpcomingBirthdays extends BaseWidget
{
    protected function getTableHeading(): string
    {
        return "Aniversariantes do Mês";
    }

    protected function getTableQuery(): Builder
    {
        return Patient::whereMonth('birthday', '=', Carbon::now()->format('m'))->whereMonth('birthday', '=', Carbon::now()->format('m'))->orderByRaw('day(birthday) asc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Paciente'),
            Tables\Columns\TextColumn::make('birthday')
                ->label('Aniversario')
                ->date('d/m'),
        ];
    }
}

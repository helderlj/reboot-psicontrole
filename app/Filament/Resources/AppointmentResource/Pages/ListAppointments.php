<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;
}

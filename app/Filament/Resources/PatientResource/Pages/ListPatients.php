<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PatientResource;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;
}

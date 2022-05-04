<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PatientResource;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;
}

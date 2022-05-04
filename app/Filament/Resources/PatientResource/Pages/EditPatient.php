<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PatientResource;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;
}

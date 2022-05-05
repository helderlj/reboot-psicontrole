<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class AvailabilitySlotPicker extends Field
{
    protected string $view = 'forms.components.availability-slot-picker';


    public $arr = [1, 2, 3];

    /**
     * @return int[]
     */
    public function getArr(): array
    {
        return $this->arr;
    }


}

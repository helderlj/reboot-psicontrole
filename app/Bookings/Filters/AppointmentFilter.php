<?php

namespace App\Bookings\Filters;

use App\Bookings\Filter;
use App\Bookings\TimeSlotGenerator;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AppointmentFilter implements Filter
{
    public $appointments;

    public function __construct(Collection $appointments)
    {
        $this->appointments = $appointments;
    }


    public function apply(TimeSlotGenerator $generator, CarbonPeriod $interval)
    {
        $interval->addFilter(function ($slot) use ($generator) {
            foreach ($this->appointments as $appointment) {
                if ($slot->between(
                    $appointment->date->setTimeFrom(
                        $appointment->start_time->subMinutes($generator->service->duration - $generator::INCREMENT)
                    ),
                    $appointment->date->setTimeFrom(
                        $appointment->end_time->subMinutes($generator::INCREMENT)
                    )
                )) {
                    return false;
                }
            }
            return true;
        });
    }
}

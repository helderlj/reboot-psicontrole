<?php

namespace App\Forms\Components;

use App\Models\Schedule;
use App\Models\Service;
use App\Models\User;
use Filament\Forms\Components\Field;

use Closure;

class AvailabilitySlotPicker extends Field
{
    protected string $view = 'forms.components.availability-slot-picker';

    public $calendarStartDate;
    public $selectedTime;
    public string | Closure $service_id = "-1";
    public string | Closure $schedule_id = "-1";
    public string | Closure $user_id = "-1";
    public $user;


    public function boot(): static
    {
        $this->calendarStartDate = now();
        $this->date = now()->timestamp;
        return $this;
    }


    public function getAvailableTimeSlotsProperty()
    {
        $user = User::find($this->getUserId());
        $schedule = Schedule::where('id', $this->getScheduleId())->with('scheduleUnavailabilities')->first();
        $service = Service::find($this->getServiceId());
        if (!$service) {
            return collect();
        }
        return $user->availableTimeSlots($schedule, $service);
    }

    public function serviceId(string | Closure $service_id): static
    {
        $this->service_id = $service_id;

        return $this;
    }

    public function getServiceId(): ?int
    {
        return $this->evaluate($this->service_id);
    }

    public function scheduleId(string | Closure $schedule_id): static
    {
        $this->schedule_id = $schedule_id;

        return $this;
    }

    public function getScheduleId(): int
    {
        return $this->evaluate($this->schedule_id);
    }

    public function userId(string | Closure $user_id): static
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->evaluate($this->user_id);
    }

    public function setSelectedTime($time)
    {
        $this->selectedTime = $time;
    }

    public function getSelectedTime()
    {
        return $this->selectedTime;
    }



}

<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;


class CalendarWidget extends FullCalendarWidget
{
    public function getViewData(): array
    {
//        return [
//            [
//                'id' => 1,
//                'title' => 'Breakfast!',
//                'start' => now(),
//                'groupId' => 'event1'
//            ],
//            [
//                'id' => 2,
//                'title' => 'Meeting with Pamela',
//                'start' => now()->addHour(),
//                'groupId' => 'event1'
//            ]
//        ];
//
//

        $schedules = Schedule::where('user_id', auth()->id())->with('appointments')->get();

        $scheduleWithAppointments = $schedules->filter(function ($date) {
            if (count($date->appointments) > 0) {
                return $date;
            }
        });

        $calendar_obj = array();

        foreach ($scheduleWithAppointments as $appointments) {
            foreach ($appointments->appointments as $appointment) {
                $tmp['id'] = $appointment->id;
                $tmp['title'] = (Patient::where('id', $appointment->patient_id)->first())->name;
                $start = $appointment->date->toDateString() . " " . $appointment->start_time->toTimeString();
                $end = $appointment->date->toDateString() . " " . $appointment->end_time->toTimeString();
                $tmp['start'] = $start;
                $tmp['end'] = $end;
                $calendar_obj[] = $tmp;
            }
        }

        return $calendar_obj;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleUnavailabilityResource;
use App\Http\Resources\ScheduleUnavailabilityCollection;

class ScheduleScheduleUnavailabilitiesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Schedule $schedule)
    {
        $this->authorize('view', $schedule);

        $search = $request->get('search', '');

        $scheduleUnavailabilities = $schedule
            ->scheduleUnavailabilities()
            ->search($search)
            ->latest()
            ->paginate();

        return new ScheduleUnavailabilityCollection($scheduleUnavailabilities);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Schedule $schedule)
    {
        $this->authorize('create', ScheduleUnavailability::class);

        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
        ]);

        $scheduleUnavailability = $schedule
            ->scheduleUnavailabilities()
            ->create($validated);

        return new ScheduleUnavailabilityResource($scheduleUnavailability);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Http\Resources\ScheduleCollection;
use App\Http\Requests\ScheduleStoreRequest;
use App\Http\Requests\ScheduleUpdateRequest;

class ScheduleController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Schedule::class);

        $search = $request->get('search', '');

        $schedules = Schedule::search($search)
            ->latest()
            ->paginate();

        return new ScheduleCollection($schedules);
    }

    /**
     * @param \App\Http\Requests\ScheduleStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleStoreRequest $request)
    {
        $this->authorize('create', Schedule::class);

        $validated = $request->validated();

        $schedule = Schedule::create($validated);

        return new ScheduleResource($schedule);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Schedule $schedule)
    {
        $this->authorize('view', $schedule);

        return new ScheduleResource($schedule);
    }

    /**
     * @param \App\Http\Requests\ScheduleUpdateRequest $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleUpdateRequest $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $validated = $request->validated();

        $schedule->update($validated);

        return new ScheduleResource($schedule);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Schedule $schedule)
    {
        $this->authorize('delete', $schedule);

        $schedule->delete();

        return response()->noContent();
    }
}

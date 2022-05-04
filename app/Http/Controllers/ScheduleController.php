<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
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
            ->paginate(5)
            ->withQueryString();

        return view('app.schedules.index', compact('schedules', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Schedule::class);

        $users = User::pluck('name', 'id');

        return view('app.schedules.create', compact('users'));
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

        return redirect()
            ->route('schedules.edit', $schedule)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Schedule $schedule)
    {
        $this->authorize('view', $schedule);

        return view('app.schedules.show', compact('schedule'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Schedule $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);

        $users = User::pluck('name', 'id');

        return view('app.schedules.edit', compact('schedule', 'users'));
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

        return redirect()
            ->route('schedules.edit', $schedule)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('schedules.index')
            ->withSuccess(__('crud.common.removed'));
    }
}

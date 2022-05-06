<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;

class ServiceAppointmentsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Service $service)
    {
        $this->authorize('view', $service);

        $search = $request->get('search', '');

        $appointments = $service
            ->appointments()
            ->search($search)
            ->latest()
            ->paginate();

        return new AppointmentCollection($appointments);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s'],
            'uuid' => ['required', 'max:255'],
            'token' => ['required', 'max:255', 'string'],
            'cancelled_at' => ['nullable', 'date'],
            'patient_id' => ['required', 'exists:patients,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
        ]);

        $appointment = $service->appointments()->create($validated);

        return new AppointmentResource($appointment);
    }
}

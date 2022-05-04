<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientCollection;

class UserPatientsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $patients = $user
            ->patients()
            ->search($search)
            ->latest()
            ->paginate();

        return new PatientCollection($patients);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Patient::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'max:255', 'string'],
            'phone_alt' => ['nullable', 'max:255', 'string'],
            'birthday' => ['required', 'date'],
            'starting_date' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'summary' => ['nullable', 'max:255', 'string'],
        ]);

        $patient = $user->patients()->create($validated);

        return new PatientResource($patient);
    }
}

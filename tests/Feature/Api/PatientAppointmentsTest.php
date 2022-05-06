<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientAppointmentsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_patient_appointments()
    {
        $patient = Patient::factory()->create();
        $appointments = Appointment::factory()
            ->count(2)
            ->create([
                'patient_id' => $patient->id,
            ]);

        $response = $this->getJson(
            route('api.patients.appointments.index', $patient)
        );

        $response->assertOk()->assertSee($appointments[0]->token);
    }

    /**
     * @test
     */
    public function it_stores_the_patient_appointments()
    {
        $patient = Patient::factory()->create();
        $data = Appointment::factory()
            ->make([
                'patient_id' => $patient->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.patients.appointments.store', $patient),
            $data
        );

        unset($data['start_time']);
        unset($data['end_time']);
        unset($data['uuid']);
        unset($data['token']);
        unset($data['cancelled_at']);
        unset($data['patient_id']);
        unset($data['schedule_id']);
        unset($data['service_id']);
        unset($data['user_id']);
        unset($data['date']);

        $this->assertDatabaseHas('appointments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $appointment = Appointment::latest('id')->first();

        $this->assertEquals($patient->id, $appointment->patient_id);
    }
}

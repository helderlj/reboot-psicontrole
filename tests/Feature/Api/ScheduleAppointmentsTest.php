<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleAppointmentsTest extends TestCase
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
    public function it_gets_schedule_appointments()
    {
        $schedule = Schedule::factory()->create();
        $appointments = Appointment::factory()
            ->count(2)
            ->create([
                'schedule_id' => $schedule->id,
            ]);

        $response = $this->getJson(
            route('api.schedules.appointments.index', $schedule)
        );

        $response->assertOk()->assertSee($appointments[0]->token);
    }

    /**
     * @test
     */
    public function it_stores_the_schedule_appointments()
    {
        $schedule = Schedule::factory()->create();
        $data = Appointment::factory()
            ->make([
                'schedule_id' => $schedule->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.schedules.appointments.store', $schedule),
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

        $this->assertEquals($schedule->id, $appointment->schedule_id);
    }
}

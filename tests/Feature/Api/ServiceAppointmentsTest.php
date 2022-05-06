<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceAppointmentsTest extends TestCase
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
    public function it_gets_service_appointments()
    {
        $service = Service::factory()->create();
        $appointments = Appointment::factory()
            ->count(2)
            ->create([
                'service_id' => $service->id,
            ]);

        $response = $this->getJson(
            route('api.services.appointments.index', $service)
        );

        $response->assertOk()->assertSee($appointments[0]->token);
    }

    /**
     * @test
     */
    public function it_stores_the_service_appointments()
    {
        $service = Service::factory()->create();
        $data = Appointment::factory()
            ->make([
                'service_id' => $service->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.services.appointments.store', $service),
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

        $this->assertDatabaseHas('appointments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $appointment = Appointment::latest('id')->first();

        $this->assertEquals($service->id, $appointment->service_id);
    }
}

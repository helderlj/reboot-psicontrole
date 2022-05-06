<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Appointment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAppointmentsTest extends TestCase
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
    public function it_gets_user_appointments()
    {
        $user = User::factory()->create();
        $appointments = Appointment::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(
            route('api.users.appointments.index', $user)
        );

        $response->assertOk()->assertSee($appointments[0]->token);
    }

    /**
     * @test
     */
    public function it_stores_the_user_appointments()
    {
        $user = User::factory()->create();
        $data = Appointment::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.appointments.store', $user),
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

        $this->assertEquals($user->id, $appointment->user_id);
    }
}

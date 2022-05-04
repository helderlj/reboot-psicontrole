<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Schedule;
use App\Models\ScheduleUnavailability;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleScheduleUnavailabilitiesTest extends TestCase
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
    public function it_gets_schedule_schedule_unavailabilities()
    {
        $schedule = Schedule::factory()->create();
        $scheduleUnavailabilities = ScheduleUnavailability::factory()
            ->count(2)
            ->create([
                'schedule_id' => $schedule->id,
            ]);

        $response = $this->getJson(
            route('api.schedules.schedule-unavailabilities.index', $schedule)
        );

        $response->assertOk()->assertSee($scheduleUnavailabilities[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_schedule_schedule_unavailabilities()
    {
        $schedule = Schedule::factory()->create();
        $data = ScheduleUnavailability::factory()
            ->make([
                'schedule_id' => $schedule->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.schedules.schedule-unavailabilities.store', $schedule),
            $data
        );

        unset($data['start_time']);
        unset($data['end_time']);
        unset($data['schedule_id']);

        $this->assertDatabaseHas('schedule_unavailabilities', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $scheduleUnavailability = ScheduleUnavailability::latest('id')->first();

        $this->assertEquals(
            $schedule->id,
            $scheduleUnavailability->schedule_id
        );
    }
}

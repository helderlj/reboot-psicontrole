<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Schedule;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleTest extends TestCase
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
    public function it_gets_schedules_list()
    {
        $schedules = Schedule::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.schedules.index'));

        $response->assertOk()->assertSee($schedules[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_schedule()
    {
        $data = Schedule::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.schedules.store'), $data);

        $this->assertDatabaseHas('schedules', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_schedule()
    {
        $schedule = Schedule::factory()->create();

        $user = User::factory()->create();

        $data = [
            'date' => $this->faker->date,
            'start_time' => $this->faker->time,
            'end_time' => $this->faker->time,
            'user_id' => $user->id,
        ];

        $response = $this->putJson(
            route('api.schedules.update', $schedule),
            $data
        );

        $data['id'] = $schedule->id;

        $this->assertDatabaseHas('schedules', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->deleteJson(
            route('api.schedules.destroy', $schedule)
        );

        $this->assertModelMissing($schedule);

        $response->assertNoContent();
    }
}

<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Schedule;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_schedules()
    {
        $schedules = Schedule::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('schedules.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.schedules.index')
            ->assertViewHas('schedules');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_schedule()
    {
        $response = $this->get(route('schedules.create'));

        $response->assertOk()->assertViewIs('app.schedules.create');
    }

    /**
     * @test
     */
    public function it_stores_the_schedule()
    {
        $data = Schedule::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('schedules.store'), $data);

        $this->assertDatabaseHas('schedules', $data);

        $schedule = Schedule::latest('id')->first();

        $response->assertRedirect(route('schedules.edit', $schedule));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get(route('schedules.show', $schedule));

        $response
            ->assertOk()
            ->assertViewIs('app.schedules.show')
            ->assertViewHas('schedule');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->get(route('schedules.edit', $schedule));

        $response
            ->assertOk()
            ->assertViewIs('app.schedules.edit')
            ->assertViewHas('schedule');
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

        $response = $this->put(route('schedules.update', $schedule), $data);

        $data['id'] = $schedule->id;

        $this->assertDatabaseHas('schedules', $data);

        $response->assertRedirect(route('schedules.edit', $schedule));
    }

    /**
     * @test
     */
    public function it_deletes_the_schedule()
    {
        $schedule = Schedule::factory()->create();

        $response = $this->delete(route('schedules.destroy', $schedule));

        $response->assertRedirect(route('schedules.index'));

        $this->assertModelMissing($schedule);
    }
}

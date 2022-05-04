<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Schedule;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSchedulesTest extends TestCase
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
    public function it_gets_user_schedules()
    {
        $user = User::factory()->create();
        $schedules = Schedule::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.schedules.index', $user));

        $response->assertOk()->assertSee($schedules[0]->date);
    }

    /**
     * @test
     */
    public function it_stores_the_user_schedules()
    {
        $user = User::factory()->create();
        $data = Schedule::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.schedules.store', $user),
            $data
        );

        $this->assertDatabaseHas('schedules', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $schedule = Schedule::latest('id')->first();

        $this->assertEquals($user->id, $schedule->user_id);
    }
}

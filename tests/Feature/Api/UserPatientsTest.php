<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Patient;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPatientsTest extends TestCase
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
    public function it_gets_user_patients()
    {
        $user = User::factory()->create();
        $patients = Patient::factory()
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.patients.index', $user));

        $response->assertOk()->assertSee($patients[0]->name);
    }

    /**
     * @test
     */
    public function it_stores_the_user_patients()
    {
        $user = User::factory()->create();
        $data = Patient::factory()
            ->make([
                'user_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.patients.store', $user),
            $data
        );

        $this->assertDatabaseHas('patients', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $patient = Patient::latest('id')->first();

        $this->assertEquals($user->id, $patient->user_id);
    }
}

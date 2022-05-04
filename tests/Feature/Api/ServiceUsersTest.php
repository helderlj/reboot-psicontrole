<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Service;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceUsersTest extends TestCase
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
    public function it_gets_service_users()
    {
        $service = Service::factory()->create();
        $user = User::factory()->create();

        $service->users()->attach($user);

        $response = $this->getJson(route('api.services.users.index', $service));

        $response->assertOk()->assertSee($user->name);
    }

    /**
     * @test
     */
    public function it_can_attach_users_to_service()
    {
        $service = Service::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson(
            route('api.services.users.store', [$service, $user])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $service
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_users_from_service()
    {
        $service = Service::factory()->create();
        $user = User::factory()->create();

        $response = $this->deleteJson(
            route('api.services.users.store', [$service, $user])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $service
                ->users()
                ->where('users.id', $user->id)
                ->exists()
        );
    }
}

<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Service;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServicesTest extends TestCase
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
    public function it_gets_user_services()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $user->services()->attach($service);

        $response = $this->getJson(route('api.users.services.index', $user));

        $response->assertOk()->assertSee($service->name);
    }

    /**
     * @test
     */
    public function it_can_attach_services_to_user()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $response = $this->postJson(
            route('api.users.services.store', [$user, $service])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $user
                ->services()
                ->where('services.id', $service->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_services_from_user()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $response = $this->deleteJson(
            route('api.users.services.store', [$user, $service])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $user
                ->services()
                ->where('services.id', $service->id)
                ->exists()
        );
    }
}

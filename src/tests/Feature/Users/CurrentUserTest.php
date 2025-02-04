<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurrentUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $response = $this->getJson(uri: '/api/user');
        $response->assertUnauthorized();
    }

    #[Test]
    public function it_should_show_current_user(): void
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/user');

        $response->assertOk()
            ->assertJson(value: [
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                ],
            ]);
    }
}

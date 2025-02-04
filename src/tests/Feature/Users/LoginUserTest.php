<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_successful_response_with_token(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->postJson(uri: '/api/login', data: $body);

        $response->assertOk()
            ->assertJsonStructure(structure: [
                'data' => [
                    'token',
                ],
            ]);

        $token = $response->json('data.token');
        $response = $this->withToken(token: $token)
            ->getJson(uri: '/api/user');

        $response->assertSuccessful();
    }

    #[Test]
    public function it_should_error_validation_for_incorrect_credentials(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => $user->username,
            'password' => 'bad password',
        ];

        $response = $this->postJson(uri: '/api/login', data: $body);
        $response->assertJsonValidationErrors(errors: ['username']);
    }

    #[Test]
    public function it_should_return_forbidden_for_authenticated_user(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => $user->username,
            'password' => 'bad password',
        ];

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/login', data: $body);

        $response->assertForbidden();
    }
}

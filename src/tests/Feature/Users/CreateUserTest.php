<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_create_an_user(): void
    {
        $body = [
            'username' => 'username',
            'password' => 'password',
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'phone_number' => '+51 999999999',
        ];

        $response = $this->postJson(uri: '/api/register', data: $body);

        $user = Arr::except(array: $body, keys: ['password']);

        $response->assertOk()
            ->assertJson(value: ['data' => $user]);
        $this->assertDatabaseHas(table: 'users', data: $user);
    }

    #[Test]
    public function it_should_return_error_validations_for_a_duplicated_username(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => $user->username,
            'password' => 'password',
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'phone_number' => '+51 999999999',
        ];

        $response = $this->postJson(uri: '/api/register', data: $body);

        $response->assertJsonValidationErrors(errors: ['username']);

        $newUser = Arr::except(array: $body, keys: ['password']);
        $this->assertDatabaseMissing(table: 'users', data: $newUser);
    }

    #[Test]
    public function it_should_return_error_validations_for_a_duplicated_email(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => 'username',
            'password' => 'password',
            'name' => 'John Doe',
            'email' => $user->email,
            'phone_number' => '+51 999999999',
        ];

        $response = $this->postJson(uri: '/api/register', data: $body);

        $response->assertJsonValidationErrors(errors: ['email']);

        $newUser = Arr::except(array: $body, keys: ['password']);
        $this->assertDatabaseMissing(table: 'users', data: $newUser);
    }

    #[Test]
    public function it_should_return_forbidden_for_authenticated_user(): void
    {
        $user = User::factory()
            ->create();

        $body = [
            'username' => 'username',
            'password' => 'password',
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'phone_number' => '+51 999999999',
        ];

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/register', data: $body);

        $response->assertForbidden();

        $newUser = Arr::except(array: $body, keys: ['password']);
        $this->assertDatabaseMissing(table: 'users', data: $newUser);
    }
}

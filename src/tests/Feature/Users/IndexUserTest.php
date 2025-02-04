<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $response = $this->getJson(uri: '/api/users');
        $response->assertUnauthorized();
    }

    #[Test]
    public function it_should_list_users(): void
    {
        $user = User::factory()
            ->create();

        User::factory()
            ->count(5)
            ->create();

        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/users');

        $response->assertOk()
            ->assertJsonCount(count: 6, key: 'data')
            ->assertJsonStructure(structure: [
                'data' => [
                    '*' => [
                        'id',
                        'username',
                        'name',
                        'email',
                        'phone_number',
                    ],
                ],
            ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexEventTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $response = $this->getJson(uri: '/api/events');
        $response->assertUnauthorized();
    }

    #[Test]
    public function it_should_list_events(): void
    {
        $user = User::factory()
            ->create();

        Event::factory()
            ->count(5)
            ->create();

        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/events');

        $response->assertOk()
            ->assertJsonCount(count: 5, key: 'data')
            ->assertJsonStructure(structure: [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'location',
                        'start_date',
                        'end_date',
                        'created_by',
                    ],
                ],
            ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexParticipantsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $event = Event::factory()
            ->create();

        $response = $this->getJson(uri: '/api/events/' . $event->id . '/participants');
        $response->assertUnauthorized();
    }

    #[Test]
    public function it_should_list_participants(): void
    {
        $user = User::factory()
            ->create();

        $event = Event::factory()
            ->create();

        EventParticipant::factory()
            ->state(state: ['event_id' => $event->id])
            ->count(5)
            ->create();

        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/events/' . $event->id . '/participants');

        $response->assertOk()
            ->assertJsonCount(count: 5, key: 'data')
            ->assertJsonStructure(structure: [
                'data' => [
                    '*' => [
                        'username',
                        'name',
                        'added_by',
                    ],
                ],
            ]);
    }
}

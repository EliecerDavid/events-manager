<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventParticipant;
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
    public function it_should_list_events_created_by_user(): void
    {
        $user = User::factory()
            ->create();

        Event::factory()
            ->state(state: ['created_by' => $user])
            ->count(3)
            ->create();

        Event::factory()
            ->count(5)
            ->create();
        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/events');

        $response->assertOk()
            ->assertJsonCount(count: 3, key: 'data')
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

    #[Test]
    public function it_should_list_enrolled_events(): void
    {
        $user = User::factory()
            ->create();

        $event1 = Event::factory()
            ->create();

        $event2 = Event::factory()
            ->create();

        $event3 = Event::factory()
            ->create();

        EventParticipant::factory()
            ->state(state: [
                'event_id' => $event1->id,
                'participant_id' => $user->id,
            ])
            ->create();

        EventParticipant::factory()
            ->state(state: [
                'event_id' => $event2->id,
                'participant_id' => $user->id,
            ])
            ->create();

        EventParticipant::factory()
            ->state(state: ['event_id' => $event3->id])
            ->create();

        $response = $this->actingAs(user: $user)
            ->getJson(uri: '/api/events');

        $response->assertOk()
            ->assertJsonCount(count: 2, key: 'data')
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

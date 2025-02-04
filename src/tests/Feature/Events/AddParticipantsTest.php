<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddParticipantsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $event = Event::factory()
            ->create();

        $response = $this->postJson(uri: '/api/events/' . $event->id . '/participants');
        $response->assertUnauthorized();
    }

    #[Test]
    public function it_should_add_participants(): void
    {
        $user = User::factory()
            ->create();

        $event = Event::factory()
            ->create();

        $participant1 = User::factory()
            ->create();
        $participant2 = User::factory()
            ->create();
        $participant3 = User::factory()
            ->create();

        $body = [
            $participant1->id,
            $participant2->id,
            $participant3->id,
        ];

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/events/' . $event->id . '/participants', data: $body);

        $response->assertOk()
            ->assertJson(value: [
                'data' => [
                    [
                        'username' => $participant1->username,
                        'name' => $participant1->name,
                        'added_by' => $user->username,
                    ],
                    [
                        'username' => $participant2->username,
                        'name' => $participant2->name,
                        'added_by' => $user->username,
                    ],
                    [
                        'username' => $participant3->username,
                        'name' => $participant3->name,
                        'added_by' => $user->username,
                    ],
                ],
            ]);

        $this->assertDatabaseHas(table: 'event_participant', data: [
            'participant_id' => $participant1->id,
            'added_by' => $user->id,
            'event_id' => $event->id,
        ]);
        $this->assertDatabaseHas(table: 'event_participant', data: [
            'participant_id' => $participant2->id,
            'added_by' => $user->id,
            'event_id' => $event->id,
        ]);
        $this->assertDatabaseHas(table: 'event_participant', data: [
            'participant_id' => $participant3->id,
            'added_by' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    #[Test]
    public function it_should_return_validation_errors_for_nonexistent_participants(): void
    {
        $user = User::factory()
            ->create();

        $event = Event::factory()
            ->create();

        $body = [
            Str::uuid(),
            Str::uuid(),
            Str::uuid(),
        ];

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/events/' . $event->id . '/participants', data: $body);

        $response->assertJsonValidationErrors(errors: [0, 1, 2]);
    }
}

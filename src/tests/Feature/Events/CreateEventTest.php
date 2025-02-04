<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_return_unauthorized(): void
    {
        $response = $this->postJson(uri: '/api/events');
        $response->assertUnauthorized();
    }

    public static function eventProvider(): array
    {
        return [
            'complete event' => [
                [
                    'title' => 'A new event',
                    'description' => 'The greatest event of the year',
                    'location' => '742 Evergreen Terrace',
                    'start_date' => '2025-05-02',
                    'end_date' => '2025-05-03',
                ],
            ],
            'minimal event' => [
                [
                    'title' => 'A new event',
                ],
            ],
        ];
    }

    #[Test]
    #[DataProvider('eventProvider')]
    public function it_should_create_an_event(array $body): void
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/events', data: $body);

        $response->assertCreated()
            ->assertJson(value: [
                'data' => [
                    ...$body,
                    'created_by' => $user->username,
                ],
            ]);

        $this->assertDatabaseHas(table: 'events', data: [
            ...$body,
            'created_by' => $user->id,
        ]);
    }

    public static function incompleteEventProvider(): array
    {
        return [
            'empty event' => [
                [], ['title'],
            ],
            'start_date and end_date are incorrect dates' => [
                [
                    'title' => 'A new event',
                    'start_date' => '2025-02-29',
                    'end_date' => '2025-12-32',
                ],
                ['start_date', 'end_date'],
            ],
            'end_date is less than start_date' => [
                [
                    'title' => 'A new event',
                    'start_date' => '2025-01-05',
                    'end_date' => '2024-12-31',
                ],
                ['end_date'],
            ],
        ];
    }

    #[Test]
    #[DataProvider('incompleteEventProvider')]
    public function it_should_return_validation_errors(array $body, array $validationErrors): void
    {
        $user = User::factory()
            ->create();

        $response = $this->actingAs(user: $user)
            ->postJson(uri: '/api/events', data: $body);

        $response->assertJsonValidationErrors(errors: $validationErrors);
    }
}

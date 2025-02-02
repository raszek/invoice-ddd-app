<?php

declare(strict_types=1);

namespace Tests\Unit\Notification\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Notifications\Application\Services\NotificationService;
use Modules\Shared\Domain\Bus\EventBus;
use PHPUnit\Framework\TestCase;

final class NotificationServiceTest extends TestCase
{
    use WithFaker;

    private EventBus $bus;

    private NotificationService $notificationService;

    protected function setUp(): void
    {
        $this->setUpFaker();

        $this->bus = new class implements EventBus {

            public array $publishedEvents = [];

            public function publish(array $events): void
            {
                $this->publishedEvents = $events;
            }
        };

        $this->notificationService = new NotificationService($this->bus);
    }

    public function testDelivered(): void
    {
        $this->notificationService->delivered($this->faker->uuid());

        $this->assertCount(1, $this->bus->publishedEvents);
    }
}

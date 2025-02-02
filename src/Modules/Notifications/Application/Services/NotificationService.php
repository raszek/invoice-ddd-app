<?php

declare(strict_types=1);

namespace Modules\Notifications\Application\Services;

use Modules\Notifications\Api\Events\ResourceDeliveredEvent;
use Modules\Shared\Domain\Bus\EventBus;
use Ramsey\Uuid\Uuid;

final readonly class NotificationService
{
    public function __construct(
        private EventBus $eventBus,
    ) {}

    public function delivered(string $reference): void
    {
        $this->eventBus->publish([
            new ResourceDeliveredEvent(
                resourceId: Uuid::fromString($reference),
            )
        ]);
    }
}

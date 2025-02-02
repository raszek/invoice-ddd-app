<?php

declare(strict_types=1);

namespace Modules\Notifications\Api\Events;

use Modules\Shared\Domain\Bus\DomainEvent;
use Ramsey\Uuid\UuidInterface;

final readonly class ResourceDeliveredEvent implements DomainEvent
{
    public function __construct(
        public UuidInterface $resourceId,
    ) {}

    public function resourceId(): string
    {
        return $this->resourceId->toString();
    }
}

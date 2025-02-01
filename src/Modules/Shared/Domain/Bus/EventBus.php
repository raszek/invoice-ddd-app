<?php

namespace Modules\Shared\Domain\Bus;

interface EventBus
{

    /**
     * @param DomainEvent[] $events
     * @return void
     */
    public function publish(array $events): void;
}

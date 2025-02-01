<?php

namespace Modules\Shared\Infrastructure\Bus;

use Modules\Shared\Domain\Bus\EventBus;
use Illuminate\Contracts\Events\Dispatcher;

readonly class LaravelEventDispatcher implements EventBus
{

    public function __construct(
        private Dispatcher $dispatcher
    ) {
    }

    public function publish(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }
}

<?php

namespace Modules\Shared\Infrastructure\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\Domain\Bus\EventBus;use Modules\Shared\Infrastructure\Bus\LaravelEventDispatcher;

final class SharedServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(EventBus::class, LaravelEventDispatcher::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            EventBus::class,
        ];
    }
}

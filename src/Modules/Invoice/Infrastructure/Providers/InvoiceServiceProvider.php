<?php

namespace Modules\Invoice\Infrastructure\Providers;

use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Modules\Invoice\Infrastructure\Repository\EloquentInvoiceRepository;

final class InvoiceServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(InvoiceRepository::class, EloquentInvoiceRepository::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            InvoiceRepository::class,
        ];
    }
}

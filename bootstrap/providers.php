<?php

use Modules\Notifications\Infrastructure\Providers\NotificationServiceProvider;
use Modules\Invoice\Infrastructure\Providers\InvoiceServiceProvider;
use Modules\Shared\Infrastructure\Providers\SharedServiceProvider;

return [
    NotificationServiceProvider::class,
    InvoiceServiceProvider::class,
    SharedServiceProvider::class
];

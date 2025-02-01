<?php

use Modules\Notifications\Infrastructure\Providers\NotificationServiceProvider;
use Modules\Invoice\Infrastructure\Providers\InvoiceServiceProvider;

return [
    NotificationServiceProvider::class,
    InvoiceServiceProvider::class,
];

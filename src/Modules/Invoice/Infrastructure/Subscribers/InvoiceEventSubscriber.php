<?php

namespace Modules\Invoice\Infrastructure\Subscribers;

use Illuminate\Events\Dispatcher;
use Modules\Invoice\Application\Action\MarkInvoiceDelivered;
use Modules\Notifications\Api\Events\ResourceDeliveredEvent;

readonly class InvoiceEventSubscriber
{

    public function __construct(
        private MarkInvoiceDelivered $markInvoiceDelivered
    ) {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ResourceDeliveredEvent::class => $this->markInvoiceDelivered(...),
        ];
    }

    public function markInvoiceDelivered(ResourceDeliveredEvent $data): void
    {
        $this->markInvoiceDelivered->execute($data->resourceId);
    }
}

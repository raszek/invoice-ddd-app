<?php

namespace Modules\Notifications\Infrastructure\Subscribers;

use Illuminate\Events\Dispatcher;
use Modules\Invoice\Domain\Event\InvoiceSentDomainEvent;
use Modules\Notifications\Application\Action\SendInvoiceEmail;

readonly class NotificationEventSubscriber
{


    public function __construct(
        private SendInvoiceEmail $sendInvoiceEmail,
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
            InvoiceSentDomainEvent::class => $this->sendEmailToCustomer(...),
        ];
    }

    public function sendEmailToCustomer(InvoiceSentDomainEvent $data): void
    {
        $this->sendInvoiceEmail->execute($data);
    }
}

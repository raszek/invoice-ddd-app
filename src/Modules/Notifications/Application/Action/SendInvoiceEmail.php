<?php

namespace Modules\Notifications\Application\Action;

use Modules\Invoice\Domain\Event\InvoiceSentDomainEvent;
use Modules\Notifications\Api\Dtos\NotifyData;
use Modules\Notifications\Api\NotificationFacadeInterface;
use Ramsey\Uuid\Uuid;

readonly class SendInvoiceEmail
{

    public function __construct(
        private NotificationFacadeInterface $notificationFacade,
    ) {
    }

    public function execute(InvoiceSentDomainEvent $data): void
    {
        $message = '';

        foreach ($data->getProducts() as $product) {
            $message .= sprintf('Product %s, product total cost: %d', $product->productName, $product->cost).PHP_EOL;
        }

        $message .= sprintf('Total cost: %d', $data->totalCost).PHP_EOL;


        $this->notificationFacade->notify(new NotifyData(
            resourceId: Uuid::fromString($data->resourceId()),
            toEmail: $data->destinationEmail,
            subject: sprintf('Hello %s here is your invoice', $data->name),
            message: $message
        ));

    }


}

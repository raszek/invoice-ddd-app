<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Shared\Domain\Bus\EventBus;

readonly class SendInvoice
{

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private EventBus $eventBus
    ) {
    }

    public function execute(string $id): void
    {
        $invoice = $this->invoiceRepository->find($id);

        $invoice->send();

        $this->invoiceRepository->update($invoice);

        $this->eventBus->publish($invoice->pullDomainEvents());
    }
}

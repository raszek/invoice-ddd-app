<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Domain\Repository\InvoiceRepository;

readonly class MarkInvoiceDelivered
{

    public function __construct(
        private InvoiceRepository $invoiceRepository,
    ) {
    }

    public function execute(string $resourceId): void
    {
        $invoice = $this->invoiceRepository->find($resourceId);

        if (!$invoice) {
            return;
        }

        $invoice->markDelivered();

        $this->invoiceRepository->update($invoice);
    }

}

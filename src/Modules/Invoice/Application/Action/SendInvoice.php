<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Domain\Repository\InvoiceRepository;

readonly class SendInvoice
{

    public function __construct(
        private InvoiceRepository $invoiceRepository,
    ) {
    }

    public function execute(string $id): void
    {
        $invoice = $this->invoiceRepository->find($id);

        $invoice->send();

        $this->invoiceRepository->update($invoice);
    }
}

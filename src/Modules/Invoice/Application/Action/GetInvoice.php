<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Application\Response\InvoiceResponse;
use Modules\Invoice\Domain\Exception\InvoiceNotExistException;
use Modules\Invoice\Domain\Repository\InvoiceRepository;

readonly class GetInvoice
{

    public function __construct(
        private InvoiceRepository $invoiceRepository,
    ) {
    }

    public function execute(string $invoiceId): InvoiceResponse
    {
        $invoice = $this->invoiceRepository->find($invoiceId);

        if (!$invoice) {
            throw new InvoiceNotExistException('Invoice not found');
        }

        return InvoiceResponse::fromInvoice($invoice);
    }

}

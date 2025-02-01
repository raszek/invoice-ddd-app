<?php

namespace Modules\Invoice\Application\Action;

use Modules\Invoice\Application\Request\InvoiceRequest;
use Modules\Invoice\Application\Response\InvoiceResponse;
use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Invoice\Domain\RootAggregate\Invoice;

readonly class CreateInvoice
{

    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }


    public function execute(InvoiceRequest $request): InvoiceResponse
    {
        $createdInvoice = Invoice::create(
            customerName: $request->customerName,
            customerEmail: $request->customerEmail,
        );

        $this->invoiceRepository->create($createdInvoice);

        return InvoiceResponse::fromInvoice($createdInvoice);
    }
}

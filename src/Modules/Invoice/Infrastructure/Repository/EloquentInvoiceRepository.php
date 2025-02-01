<?php

namespace Modules\Invoice\Infrastructure\Repository;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Invoice\Domain\RootAggregate\Invoice;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Modules\Invoice\Infrastructure\Model\ModelInvoice;

class EloquentInvoiceRepository implements InvoiceRepository
{

    public function find(string $id): ?Invoice
    {
        $modelInvoice = ModelInvoice::find($id);

        if (!$modelInvoice) {
            return null;
        }

        return new Invoice(
            id: $modelInvoice->id,
            customerName: new CustomerName($modelInvoice->customer_name),
            customerEmail: new CustomerEmail($modelInvoice->customer_email),
            status: StatusEnum::tryFrom($modelInvoice->status),
            products: []
        );
    }

    public function create(Invoice $createdInvoice): void
    {
        $newInvoice = new ModelInvoice();

        $newInvoice->id = $createdInvoice->getId();
        $newInvoice->customer_name = $createdInvoice->getCustomerName();
        $newInvoice->customer_email = $createdInvoice->getCustomerEmail();
        $newInvoice->status = $createdInvoice->getStatus()->value;

        $newInvoice->save();
    }
}

<?php

namespace Modules\Invoice\Infrastructure\Repository;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\Repository\InvoiceRepository;
use Modules\Invoice\Domain\RootAggregate\Invoice;
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
            customerName: $modelInvoice->customer_name,
            customerEmail: $modelInvoice->customer_email,
            status: StatusEnum::tryFrom($modelInvoice->status),
            products: []
        );
    }
}

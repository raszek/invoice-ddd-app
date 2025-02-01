<?php

namespace Modules\Invoice\Domain\Repository;

use Modules\Invoice\Domain\RootAggregate\Invoice;

interface InvoiceRepository
{

    public function find(string $id): ?Invoice;

    public function create(Invoice $createdInvoice): void;
}

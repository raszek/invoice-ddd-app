<?php

namespace Modules\Invoice\Domain\Repository;

use Modules\Invoice\Domain\RootAggregate\Product;

interface ProductRepository
{

    public function create(Product $product, string $invoiceId): void;
}

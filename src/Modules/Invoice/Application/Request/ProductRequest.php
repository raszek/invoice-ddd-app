<?php

namespace Modules\Invoice\Application\Request;

class ProductRequest
{
    public function __construct(
        public string $name,
        public int $quantity,
        public float $price,
        public string $invoiceId
    ) {
    }

}

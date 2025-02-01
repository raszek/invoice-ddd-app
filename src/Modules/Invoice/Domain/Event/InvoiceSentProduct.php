<?php

namespace Modules\Invoice\Domain\Event;

readonly class InvoiceSentProduct
{
    public function __construct(
        public string $productName,
        public int $cost,
    ) {
    }

}

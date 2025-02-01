<?php

namespace Modules\Invoice\Application\Request;

readonly class InvoiceRequest
{

    public function __construct(
        public string $customerName,
        public string $customerEmail,
    ) {
    }

}

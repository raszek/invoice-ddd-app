<?php

namespace Modules\Invoice\Application\Response;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\RootAggregate\Invoice;
use Modules\Invoice\Domain\RootAggregate\Product;
use Modules\Shared\Helper\ArrayHelper;
use Modules\Shared\Helper\JsonHelper;

readonly class InvoiceResponse
{
    public function __construct(
        public string $id,
        public string $customerName,
        public string $customerEmail,
        public StatusEnum $status,
        public array $products,
    ) {
    }

    public static function fromInvoice(Invoice $invoice): self
    {
        $responseProducts = ArrayHelper::map(
            $invoice->getProducts(),
            fn(Product $product) => ProductResponse::fromProduct($product),
        );

        return new self(
            id: $invoice->getId(),
            customerName: $invoice->getCustomerName(),
            customerEmail: $invoice->getCustomerEmail(),
            status: $invoice->getStatus(),
            products: $responseProducts,
        );
    }

    public function toArray(): array
    {
        $products = ArrayHelper::map($this->products, fn(ProductResponse $product) => $product->toArray());

        return [
            'id' => $this->id,
            'customerName' => $this->customerName,
            'customerEmail' => $this->customerEmail,
            'status' => $this->status->value,
            'products' => $products,
        ];
    }

}

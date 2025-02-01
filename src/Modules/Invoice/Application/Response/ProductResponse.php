<?php

namespace Modules\Invoice\Application\Response;

use Modules\Invoice\Domain\RootAggregate\Product;

readonly class ProductResponse
{
    public function __construct(
        public string $name,
        public int $quantity,
        public int $price,
    ) {
    }

    public static function fromProduct(Product $product): static
    {
        return new static(
            name: $product->getName(),
            quantity: $product->getQuantity(),
            price: $product->getPrice(),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}

<?php

namespace Modules\Invoice\Domain\Event;

use Modules\Shared\Domain\Bus\DomainEvent;

class InvoiceSentDomainEvent implements DomainEvent
{

    /**
     * @var InvoiceSentProduct[]
     */
    private array $products;

    public function __construct(
        public string $id,
        public string $destinationEmail,
        public string $name,
        public int $totalCost,
        array $products
    ) {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    public function addProduct(InvoiceSentProduct $product): void
    {
        $this->products[] = $product;
    }

    /**
     * @return InvoiceSentProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function resourceId(): string
    {
        return $this->id;
    }
}

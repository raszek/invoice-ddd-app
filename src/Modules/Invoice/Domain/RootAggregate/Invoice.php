<?php

namespace Modules\Invoice\Domain\RootAggregate;

use Modules\Invoice\Domain\Enums\StatusEnum;
use Modules\Invoice\Domain\ValueObject\CustomerEmail;
use Modules\Invoice\Domain\ValueObject\CustomerName;
use Ramsey\Uuid\Uuid;

class Invoice
{

    public function __construct(
        private readonly string $id,
        private readonly CustomerName $customerName,
        private readonly CustomerEmail $customerEmail,
        private readonly StatusEnum $status,
        private array $products,
    ) {
        $this->setProducts($this->products);
    }

    public static function create(
        string $customerName,
        string $customerEmail,
    ): static
    {
        return new static(
            id: Uuid::uuid4()->toString(),
            customerName: new CustomerName($customerName),
            customerEmail: new CustomerEmail($customerEmail),
            status: StatusEnum::Draft,
            products: []
        );
    }

    public function setProducts(array $products): void
    {
        $this->products = [];
        $this->addProducts($products);
    }

    public function addProducts(array $products): void
    {
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

}

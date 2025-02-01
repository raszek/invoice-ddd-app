<?php

namespace Modules\Invoice\Domain\RootAggregate;

use Webmozart\Assert\Assert;

readonly class Product
{
    public function __construct(
        private string $name,
        private int $quantity,
        private int $price,
    ) {
        Assert::minLength($this->name, 1);
        Assert::positiveInteger($this->quantity);
        Assert::positiveInteger($this->price);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getTotalUnitPrice(): int
    {
        return $this->price * $this->quantity;
    }
}

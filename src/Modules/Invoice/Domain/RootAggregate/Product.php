<?php

namespace Modules\Invoice\Domain\RootAggregate;

use Modules\Invoice\Domain\ValueObject\ProductName;
use Modules\Shared\ValueObject\Uuid;
use Webmozart\Assert\Assert;

readonly class Product
{
    public function __construct(
        private Uuid $id,
        private ProductName $name,
        private int $quantity,
        private int $price,
    ) {
        Assert::minLength($this->name, 1);
        Assert::positiveInteger($this->quantity);
        Assert::positiveInteger($this->price);
    }

    public function getId(): Uuid
    {
        return $this->id;
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

<?php

namespace Modules\Invoice\Domain\ValueObject;

use Webmozart\Assert\Assert;

readonly class ProductName
{

    const int MAX_LENGTH = 100;

    public function __construct(
        private string $name,
    ) {
        Assert::minLength($this->name, 1);
        Assert::maxLength($this->name, self::MAX_LENGTH);
    }

    public function __toString(): string
    {
        return $this->name;
    }

}

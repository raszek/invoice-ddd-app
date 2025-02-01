<?php

namespace Modules\Invoice\Domain\ValueObject;

use Webmozart\Assert\Assert;

readonly class CustomerName
{

    const int MAX_LENGTH = 255;

    public function __construct(
        private string $name,
    ) {
        Assert::notEmpty($this->name);
        Assert::maxLength($this->name, self::MAX_LENGTH);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

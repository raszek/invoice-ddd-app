<?php

namespace Modules\Invoice\Domain\ValueObject;

use Webmozart\Assert\Assert;

readonly class CustomerEmail
{

    const int MAX_LENGTH = 255;

    public function __construct(
        private string $email,
    ) {
        Assert::notEmpty($this->email);
        Assert::maxLength($this->email, self::MAX_LENGTH);
        Assert::email($this->email);
    }

    public function __toString(): string
    {
        return $this->email;
    }

}

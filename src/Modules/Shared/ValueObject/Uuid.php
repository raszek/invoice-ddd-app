<?php

namespace Modules\Shared\ValueObject;

use Webmozart\Assert\Assert;

readonly class Uuid
{
    public function __construct(
        private string $uuid
    ) {
        Assert::uuid($this->uuid);
    }

    public static function create(): static
    {
        return new static(
            \Ramsey\Uuid\Uuid::uuid4()->toString()
        );
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}

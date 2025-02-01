<?php

namespace Modules\Shared\Domain\Bus;

interface DomainEvent
{

    public function resourceId(): string;
}

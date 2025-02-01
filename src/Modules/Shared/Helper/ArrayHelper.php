<?php

namespace Modules\Shared\Helper;

class ArrayHelper
{

    public static function map(array $items, callable $callback): array
    {
        return array_map($callback, $items);
    }
}

<?php

namespace Modules\Shared\Helper;

class JsonHelper
{

    public static function encode(mixed $value): string
    {
        return json_encode($value, JSON_THROW_ON_ERROR);
    }
}

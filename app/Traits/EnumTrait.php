<?php

namespace App\Traits;

trait EnumTrait
{
    /**
     * Get the array of values from an enum
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;

trait ParseFloatTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableFloat(mixed &$value): ?float
    {
        try {
            if ((string)$value === '0') {
                return 0;
            }
            return empty($value) ? null : (float)$value;
        } catch (Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseFloat(mixed &$value, ?float $defaultValue = null): float
    {
        $castedValue = self::parseNullableFloat($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

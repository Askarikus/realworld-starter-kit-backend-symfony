<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;

trait ParseIntTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableInt(mixed &$value): ?int
    {
        try {
            if ((string)$value === '0') {
                return 0;
            }
            return empty($value) ? null : (int)$value;
        } catch (Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseInt(mixed &$value, ?int $defaultValue = null): int
    {
        $castedValue = self::parseNullableInt($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

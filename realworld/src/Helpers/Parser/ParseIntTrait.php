<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

trait ParseIntTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableInt(mixed &$value): ?int
    {
        try {
            if ('0' === (string) $value) {
                return 0;
            }

            return empty($value) ? null : (int) $value;
        } catch (\Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseInt(mixed &$value, ?int $defaultValue = null): int
    {
        $castedValue = self::parseNullableInt($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}

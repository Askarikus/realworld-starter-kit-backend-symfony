<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

trait ParseBoolTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableBool(mixed &$value): ?bool
    {
        try {
            if (null === $value) {
                return null;
            }

            return (bool) $value;
        } catch (\Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseBool(mixed &$value, ?bool $defaultValue = null): bool
    {
        $castedValue = self::parseNullableBool($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}

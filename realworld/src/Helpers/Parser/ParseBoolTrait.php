<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;

trait ParseBoolTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableBool(mixed &$value): ?bool
    {
        try {
            if ($value === null) {
                return null;
            }
            return (bool)$value;
        } catch (Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseBool(mixed &$value, ?bool $defaultValue = null): bool
    {
        $castedValue = self::parseNullableBool($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

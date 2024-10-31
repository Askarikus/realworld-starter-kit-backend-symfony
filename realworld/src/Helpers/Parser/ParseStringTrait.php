<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

trait ParseStringTrait
{
    use PrepareParseExceptionTrait;

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseNullableString(mixed &$value): ?string
    {
        try {
            if (null === $value) {
                return null;
            }

            return (string) $value;
        } catch (\Exception $error) {
            throw self::prepareParseException($error->getMessage());
        }
    }

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseString(mixed &$value, ?string $defaultValue = null): string
    {
        $castedValue = self::parseNullableString($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}

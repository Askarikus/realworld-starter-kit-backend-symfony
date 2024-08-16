<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait ParseStringTrait
{
    use PrepareParseExceptionTrait;

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseNullableString(mixed &$value): ?string
    {
        try {
            if ($value === null) {
                return null;
            }

            return (string)$value;
        } catch (Exception $error) {
            throw self::prepareParseException($error->getMessage());
        }
    }

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseString(mixed &$value, ?string $defaultValue = null): string
    {
        $castedValue = self::parseNullableString($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }

        return $castedValue;
    }
}

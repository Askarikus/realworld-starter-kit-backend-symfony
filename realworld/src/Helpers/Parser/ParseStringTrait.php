<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait ParseStringTrait
{
    use PrepareParseExceptionTrait;

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseNullableString(mixed &$value, ?int $maxLength = 200): ?string
    {
        try {
            if ($value === null) {
                return null;
            }

            if ($maxLength !== null && mb_strlen((string)$value) > $maxLength) {
                return throw new BadRequestException("String length cannot exceed {$maxLength} characters");
            }

            return (string)$value;
        } catch (Exception $error) {
            throw self::prepareParseException($error->getMessage());
        }
    }

    // @description Reference(&) needed for passing Undefined array keys
    protected static function parseString(mixed &$value, ?int $maxLength = 200, ?string $defaultValue = null): string
    {
        $castedValue = self::parseNullableString($value, $maxLength);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }

        return $castedValue;
    }
}

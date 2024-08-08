<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;

trait ParseDateTimeImmutableTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableDateTimeImmutable(mixed &$value): ?\DateTimeImmutable
    {
        try {
            if ($value === null) {
                return null;
            }
            return empty($value) ? null : \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        } catch (Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseDateTimeImmutable(mixed &$value, ?float $defaultValue = null): \DateTimeImmutable
    {
        $castedValue = self::parseNullableDateTimeImmutable($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

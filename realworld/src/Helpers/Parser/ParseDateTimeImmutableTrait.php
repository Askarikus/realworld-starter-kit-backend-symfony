<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

trait ParseDateTimeImmutableTrait
{
    use PrepareParseExceptionTrait;

    protected static function parseNullableDateTimeImmutable(mixed &$value): ?\DateTimeImmutable
    {
        try {
            if (null === $value) {
                return null;
            }

            return empty($value) ? null : \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        } catch (\Exception) {
            throw self::prepareParseException();
        }
    }

    protected static function parseDateTimeImmutable(mixed &$value, ?float $defaultValue = null): \DateTimeImmutable
    {
        $castedValue = self::parseNullableDateTimeImmutable($value);
        if (null === $castedValue) {
            if (null === $defaultValue) {
                throw self::prepareParseException();
            }

            return $defaultValue;
        }

        return $castedValue;
    }
}

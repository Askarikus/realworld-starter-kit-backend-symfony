<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use Exception;

trait ParseArrayTrait
{
    use PrepareParseExceptionTrait;

    /**
     * @return array<mixed>
     */
    protected static function parseNullableArray(mixed &$value): ?array
    {
        if (!isset($value)) {
            return null;
        }

        if (empty($value)) {
            return [];
        }

        try {
            return (array)$value;
        } catch (Exception) {
            throw self::prepareParseException();
        }
    }

    /**
     * @return array<mixed>
     */
    protected static function parseArray(mixed &$value, ?array $defaultValue = null): array
    {
        $castedValue = self::parseNullableArray($value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

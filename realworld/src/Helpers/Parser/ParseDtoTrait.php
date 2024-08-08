<?php

declare(strict_types=1);

namespace App\Helpers\Parser;

use App\Dto\AbstractRequestDto;
use App\Dto\AbstractResponseDto;

trait ParseDtoTrait
{
    use PrepareParseExceptionTrait;
    use ParseArrayTrait;

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    protected static function parseNullableRequestDto(string $className, mixed &$value)
    {
        $parsedValue = self::parseNullableArray($value);
        if ($parsedValue === null) {
            return;
        }

        if (!is_subclass_of($className, AbstractRequestDto::class)) {
            throw self::prepareParseException($className . ' is not instance of ' . AbstractRequestDto::class);
        }

        return $className::fromArray($parsedValue);
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @param T|null $defaultValue
     * @return T&!null
     */
    protected static function parseRequestDto(string $className, mixed &$value, $defaultValue = null)
    {
        $castedValue = self::parseNullableRequestDto($className, $value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return array<T>|null
     */
    protected static function parseNullableRequestDtoList(string $className, mixed &$value): ?array
    {
        $parsedValues = self::parseNullableArray($value);
        if ($parsedValues === null) {
            return null;
        }

        $result = [];
        foreach ($parsedValues as $parsedValue) {
            $result[] = self::parseRequestDto($className, $parsedValue);
        }

        return $result;
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @param array<T>|null $defaultValue
     * @return array<T>&!null
     */
    protected static function parseRequestDtoList(string $className, mixed &$value, $defaultValue = null): array
    {
        $castedValue = self::parseNullableRequestDtoList($className, $value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }

    /**
     * @template T
     * @param class-string<T> $className
    //  * @return T
     */
    protected static function parseNullableResponseDto(string $className, mixed $value)
    {
        if ($value === null) {
            return;
        }

        if (!is_subclass_of($className, AbstractResponseDto::class)) {
            throw self::prepareParseException($className . ' is not instance of ' . AbstractResponseDto::class);
        }
        return $className::fromModel($value);
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @param T|null $defaultValue
     * @return T&!null
     */
    protected static function parseResponseDto(string $className, mixed $value, $defaultValue = null)
    {
        $castedValue = self::parseNullableResponseDto($className, $value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return array<T>|null
     */
    protected static function parseNullableResponseDtoList(string $className, mixed &$value): ?array
    {
        $parsedValues = self::parseNullableArray($value);
        if ($parsedValues === null) {
            return null;
        }

        $result = [];
        foreach ($parsedValues as $parsedValue) {
            $result[] = self::parseResponseDto($className, $parsedValue);
        }

        return $result;
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @param array<T>|null $defaultValue
     * @return array<T>&!null
     */
    protected static function parseResponseDtoList(string $className, mixed &$value, $defaultValue = null): array
    {
        $castedValue = self::parseNullableResponseDtoList($className, $value);
        if ($castedValue === null) {
            if ($defaultValue === null) {
                throw self::prepareParseException();
            }
            return $defaultValue;
        }
        return $castedValue;
    }
}

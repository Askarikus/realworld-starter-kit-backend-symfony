<?php

declare(strict_types=1);

namespace App\Dto;

abstract class AbstractRequestDto implements \JsonSerializable
{
    /**
     * @param mixed[] $data
     */
    abstract public static function fromArray(array $data): static;

    /**
     * @return mixed[]
     */
    abstract public function jsonSerialize(): array;
}

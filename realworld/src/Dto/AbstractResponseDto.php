<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\AbstractEntity;

abstract class AbstractResponseDto implements \JsonSerializable
{
    abstract public static function fromModel(AbstractEntity $model): static;

    /**
     * @return mixed[]
     */
    abstract public function jsonSerialize(): array;
}

<?php

namespace App\Dto\Tag;

use App\Dto\AbstractResponseDto;
use App\Entity\Tag;
use App\Entity\AbstractEntity;

final class TagResponseDto extends AbstractResponseDto
{
    public function __construct(
        private readonly string $id,
        private readonly string $name
    ) {
    }

    /**
     * @param Tag $model
     */
    public static function fromModel(AbstractEntity|Tag $model): static
    {
        return new static(
            id: $model->getId(),
            name: $model->getName()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}

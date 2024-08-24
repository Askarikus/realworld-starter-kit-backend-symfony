<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Entity\User;
use App\Entity\UserEntity;
use App\Entity\AbstractEntity;
use App\Dto\AbstractResponseDto;

final class UserProfileResponseDto extends AbstractResponseDto
{
    public function __construct(
        private readonly string $name,
        private readonly ?string $bio = null,
        private readonly ?string $image = null,
    ) {
    }

    /**
     * @param UserEntity $model
     */
    public static function fromModel(AbstractEntity|User $model): static
    {
        return new static(
            name: $model->getName(),
            bio: $model->getBio(),
            image: $model->getImage()
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getBio(): ?string
    {
        return $this->bio;
    }
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'username' => $this->getName(),
            'bio' => $this->getBio(),
            'image' => $this->getImage(),
        ];
    }
}

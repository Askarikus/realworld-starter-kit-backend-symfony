<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Dto\AbstractResponseDto;
use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Entity\UserEntity;

final class UserProfileResponseDto extends AbstractResponseDto
{
    private bool $isFollowedByCeleb = false;

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

    public function getIsFollowedByCeleb(): bool
    {
        return $this->isFollowedByCeleb;
    }

    public function setIsFollowedByCeleb(bool $isFollowedByCeleb): void
    {
        $this->isFollowedByCeleb = $isFollowedByCeleb;
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
            'follow' => $this->getIsFollowedByCeleb(),
        ];
    }
}

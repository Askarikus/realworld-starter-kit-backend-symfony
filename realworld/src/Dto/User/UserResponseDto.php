<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Entity\User;
use App\Entity\UserEntity;
use App\Entity\AbstractEntity;
use App\Dto\AbstractResponseDto;
use Symfony\Component\Uid\UuidV7;

final class UserResponseDto extends AbstractResponseDto
{
    public function __construct(
        private readonly UuidV7 $id,
        private readonly string $email,
        private readonly string $name,
        private readonly ?string $bio = null,
        private readonly ?string $image = null

    ) {
    }

    /**
     * @param UserEntity $model
     */
    public static function fromModel(AbstractEntity|User $model): static
    {
        return new static(
            id: $model->getId(),
            email: $model->getEmail(),
            name: $model->getName(),
            bio: $model->getBio(),
            image: $model->getImage()
        );
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
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
            'id' => (string) $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'bio' => $this->getBio(),
            'image' => $this->getImage()
        ];
    }
}

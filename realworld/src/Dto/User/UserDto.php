<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Dto\AbstractResponseDto;
use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Entity\UserEntity;
use Symfony\Component\Uid\UuidV7;

final class UserDto extends AbstractResponseDto
{
    public function __construct(
        private readonly UuidV7 $id,
        private readonly string $email,
        private readonly string $name,
        private readonly ?string $bio = null,
        private readonly ?string $image = null,

        private readonly ?\DateTimeImmutable $created_at,
        private readonly ?\DateTimeImmutable $updated_at,
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
            image: $model->getImage(),
            created_at: $model->getCreatedAt(),
            updated_at: $model->getUpdatedAt(),
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
        return $this->name;
    }

    public function getImage(): ?string
    {
        return $this->name;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
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
            'image' => $this->getImage(),
            'created_at' => $this->getCreatedAt()?->getTimestamp(),
            'updated_at' => $this->getUpdatedAt()?->getTimestamp(),
        ];
    }
}

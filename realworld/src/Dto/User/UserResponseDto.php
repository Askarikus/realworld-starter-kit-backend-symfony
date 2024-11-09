<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Dto\AbstractResponseDto;
use App\Entity\AbstractEntity;
use App\Entity\User;
use App\Entity\UserEntity;
use Symfony\Component\Uid\UUidV7;

final class UserResponseDto extends AbstractResponseDto
{
    private ?string $jwtToken = null;

    public function __construct(
        private readonly UUidV7 $id,
        private readonly string $email,
        private readonly string $username,
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
            id: $model->getId(),
            email: $model->getEmail(),
            username: $model->getUsername(),
            bio: $model->getBio(),
            image: $model->getImage()
        );
    }

    public function getId(): UUidV7
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setJwtToken(?string $jwtToken): void
    {
        $this->jwtToken = $jwtToken;
    }

    public function getJwtToken(): ?string
    {
        return $this->jwtToken;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            // 'id' => (string) $this->getId(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'bio' => $this->getBio(),
            'image' => $this->getImage(),
            'token' => $this->getJwtToken(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Dto\AbstractRequestDto;
use App\Helpers\Parser\ParseDataTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class EditUserRequestDto extends AbstractRequestDto
{
    use ParseDataTrait;

    public function __construct(
        #[Assert\Type('string')]
        private readonly ?string $email,

        #[Assert\Type('string')]
        private readonly ?string $password,

        #[Assert\Type('string')]
        private readonly ?string $name,

        #[Assert\Type('string')]
        private readonly ?string $bio,

        #[Assert\Type('string')]
        private readonly ?string $image,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            email: self::parseNullableString($data['email']),
            password: self::parseNullableString($data['password']),
            name: self::parseNullableString($data['username']),
            bio: self::parseNullableString($data['bio']),
            image: self::parseNullableString($data['image']),
        );
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getPassword(): ?string
    {
        return $this->password;
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

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'name' => $this->getName(),
            'bio' => $this->getBio(),
            'image' => $this->getImage(),
        ];
    }
}

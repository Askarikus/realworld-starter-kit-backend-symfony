<?php

declare(strict_types=1);

namespace App\Dto\User;

use App\Dto\AbstractRequestDto;
use App\Helpers\Parser\ParseDataTrait;
use Symfony\Component\Validator\Constraints as Assert;

final class LoginUserRequestDto extends AbstractRequestDto
{
    use ParseDataTrait;

    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\NotNull]
        private readonly string $email,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\NotNull]
        private readonly string $password,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            email: self::parseString($data['email']),
            password: self::parseString($data['password'])
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function jsonSerialize(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Helpers\Password;

use App\Contracts\PasswordHasherInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherHelper implements PasswordHasherInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hash(User $user, string $plainPassword): string
    {
        return $this->userPasswordHasher->hashPassword($user, $plainPassword);
    }

    public function verify(User $user, string $plainPassword): bool
    {
        return $this->userPasswordHasher->isPasswordValid($user, $plainPassword);
    }
}

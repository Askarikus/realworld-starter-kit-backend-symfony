<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\User;

interface PasswordHasherInterface
{
    public function hash(User $user, string $password): string;

    public function verify(User $user, string $password): bool;
}

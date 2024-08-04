<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\Admin;
use App\Entity\User;

interface PasswordHasherInterface
{
    public function hash(User $user, string $password): string;
}

<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use App\Repository\UserRepository;

class GetUserByNameUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function execute(string $username): ?User
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }
}

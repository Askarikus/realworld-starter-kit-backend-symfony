<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Repository\UserRepository;

class IsUserWithEmailExistUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function execute(string $email): bool
    {
        $result = $this->userRepository->findEmailByEmail($email);

        return null !== $result;
    }
}

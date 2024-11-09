<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\User\RegisterUserRequestDto;
use App\Entity\User;
use App\Exception\User\UserAlreadyExistException;

class RegisterUserUseCase
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly IsUserWithEmailExistUseCase $checkSameUserByEmailUseCase,
    ) {
    }

    public function execute(RegisterUserRequestDto $registerUserRequestDto): User
    {
        $userExists = $this->checkSameUserByEmailUseCase->execute($registerUserRequestDto->getEmail());
        if ($userExists) {
            throw new UserAlreadyExistException();
        }
        $user = $this->createUserUseCase->execute(
            $registerUserRequestDto->getEmail(),
            $registerUserRequestDto->getPassword(),
            $registerUserRequestDto->getUsername(),
        );

        return $user;
    }
}

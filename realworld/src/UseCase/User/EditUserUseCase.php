<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\User\EditUserRequestDto;
use App\Entity\User;
use App\Helpers\Password\PasswordHasherHelper;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherHelper $passwordHasherHelper,
    ) {
    }

    public function execute(
        string $userId,
        EditUserRequestDto $editUserRequestDto,
    ): ?User {
        $user = $this->userRepository->find($userId);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $fields = ['email', 'name', 'bio', 'image'];
        foreach ($fields as $field) {
            if (method_exists($editUserRequestDto, 'get'.ucfirst($field))) {
                if ($editUserRequestDto->{'get'.ucfirst($field)}()) {
                    if (method_exists($user, 'set'.ucfirst($field))) {
                        $user->{'set'.ucfirst($field)}($editUserRequestDto->{'get'.ucfirst($field)}());
                    }
                }
            }
        }

        if ($editUserRequestDto->getPassword()) {
            $user->setPassword(
                password: $editUserRequestDto->getPassword(),
                passwordHasher: $this->passwordHasherHelper,
            );
        }

        $user = $this->userRepository->save($user);

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Helpers\Password\PasswordHasherHelper;

class CreateUserUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasherHelper $passwordHasherHelper,
    ) {

    }

    public function execute(
        string $email,
        string $password,
        ?string $name = null,
        ?string $bio = null,
        ?string $image = null,
    ): User {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(
            password: $password,
            passwordHasher: $this->passwordHasherHelper,
        );
        $fields = ['bio', 'image', 'name'];

        foreach ($fields as $field) {
            $setter = 'set' . ucfirst($field);
            if (method_exists($user, $setter) && ${$field}) {
                $user->{$setter}(${$field});
            }
        }

        $user = $this->userRepository->save($user);

        return $user;

    }

}

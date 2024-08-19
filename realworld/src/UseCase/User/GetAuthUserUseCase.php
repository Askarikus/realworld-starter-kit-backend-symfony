<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use Webmozart\Assert\Assert;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class GetAuthUserUseCase
{
    public function __construct(private readonly Security $security)
    {
    }

    public function execute(): User
    {
        /** @var UserInterface $user */
        $user = $this->security->getUser();

        Assert::notNull($user, 'Current user not found check security access list');
        Assert::isInstanceOf($user, User::class, sprintf('Invalid user type %s', get_class($user)));

        return $user;
    }
}

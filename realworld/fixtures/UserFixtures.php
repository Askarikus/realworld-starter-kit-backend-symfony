<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\User;
use Symfony\Component\Uid\UUidV7;
use Doctrine\Persistence\ObjectManager;
use App\Helpers\Password\PasswordHasherHelper;

class UserFixtures extends BaseFixtures
{
    public function __construct(
        private readonly PasswordHasherHelper $passwordHasherHelper,
    ) {
    }

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            User::class,
            1,
            [],
            function (User $user, $arr) {
                // $user->setId(UUidV7::v4());
                $user->setUsername('user');
                $user->setEmail('user@user.com');
                $user->setPassword(
                    password: '1234567',
                    // password: $this->faker->password(),
                    passwordHasher: $this->passwordHasherHelper,
                );
                $user->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-200 days')));
                // $user->setUpdatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days')));
            }
        );

        $this->manager->flush();
    }

    public function getOrder(): int
    {
        return 1; // smaller means sooner
    }
}

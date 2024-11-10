<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return User::class;
    }

    public function save(User $user): User
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    public function findEmailByEmail(string $email): ?string
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->select('u.email')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        return $result['email'] ?? null;
    }
}

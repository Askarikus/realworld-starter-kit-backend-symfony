<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Follow;

class FollowRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return Follow::class;
    }

    public function save(Follow $follow): Follow
    {
        $this->getEntityManager()->persist($follow);
        $this->getEntityManager()->flush();

        return $follow;
    }

    public function delete(Follow $follow): void
    {
        $this->getEntityManager()->remove($follow);
        $this->getEntityManager()->flush();
    }
}

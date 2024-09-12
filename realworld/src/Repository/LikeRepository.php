<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Like;

class LikeRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return Like::class;
    }

    public function save(Like $like): Like
    {
        $this->getEntityManager()->persist($like);
        $this->getEntityManager()->flush();

        return $like;
    }

    public function delete(Like $like): void
    {
        $this->getEntityManager()->remove($like);
        $this->getEntityManager()->flush();
    }
}

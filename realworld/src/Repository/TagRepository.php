<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;

class TagRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return Tag::class;
    }

    public function save(Tag $tag): Tag
    {
        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();

        return $tag;
    }

    public function delete(Tag $tag): void
    {
        $this->getEntityManager()->remove($tag);
        $this->getEntityManager()->flush();
    }
}

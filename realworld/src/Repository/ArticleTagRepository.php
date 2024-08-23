<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ArticleTag;

class ArticleTagRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return ArticleTag::class;
    }

    public function save(ArticleTag $articleTag): ArticleTag
    {
        $this->getEntityManager()->persist($articleTag);
        $this->getEntityManager()->flush();

        return $articleTag;
    }

    public function delete(ArticleTag $articleTag): void
    {
        $this->getEntityManager()->remove($articleTag);
        $this->getEntityManager()->flush();
    }
}

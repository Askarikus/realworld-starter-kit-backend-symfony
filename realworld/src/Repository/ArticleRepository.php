<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;

class ArticleRepository extends AbstractRepository
{
    public function entityClass(): string
    {
        return Article::class;
    }

    public function save(Article $article): Article
    {
        $this->getEntityManager()->persist($article);
        $this->getEntityManager()->flush();

        return $article;
    }

    public function delete(Article $article): void
    {
        $this->getEntityManager()->remove($article);
        $this->getEntityManager()->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;

class GetArticleBySlugUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    public function execute(string $slug): ?Article
    {
        $article = $this->articleRepository->findOneBy([
            'slug' => $slug,
        ]);

        return $article;
    }
}

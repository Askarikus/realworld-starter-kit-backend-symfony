<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\ArticleRepository;

class GetArticleByUserIdUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository
    ) {
    }

    public function execute(string $userId)
    {
        return $this->articleRepository->findBy(['author' => $userId]);
    }
}

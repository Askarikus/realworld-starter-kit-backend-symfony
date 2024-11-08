<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;

class GetArticleByIdUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    public function execute(string $id): ?Article
    {
        return $this->articleRepository->find($id);
    }
}

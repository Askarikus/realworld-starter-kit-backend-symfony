<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\ArticleRepository;

class DeleteArticleUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    public function execute(int $id): bool
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            return false;
        }

        $this->articleRepository->delete($article);

        return true;
    }
}

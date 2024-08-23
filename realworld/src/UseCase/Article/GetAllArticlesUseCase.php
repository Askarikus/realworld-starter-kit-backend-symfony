<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\ArticleRepository;

class GetAllArticlesUseCase
{

    public function __construct(
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    public function execute()
    {
        $allArticles = $this->articleRepository->findAll();

        return $allArticles;
    }
}

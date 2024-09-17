<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\ArticleRepository;
use App\Repository\ArticleTagRepository;
use App\UseCase\Tag\GetTagByNameUseCase;

class GetArticlesByTagUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly ArticleTagRepository $articleTagRepository,
        private readonly GetTagByNameUseCase $getTagByNameUseCase,
    ) {
    }

    /**
     * @param string $tagName
     * @return Article[]
     */
    public function execute(string $tagName): array
    {
        $tag = $this->getTagByNameUseCase->execute($tagName);

        if (!$tag) {
            return [];
        }

        $articleTags = $this->articleTagRepository->findBy(['tagId' => $tag->getStringId()]);
        $articles = [];
        foreach ($articleTags as $articleTag) {
            $articles[] = $this->articleRepository->find($articleTag->getArticleId());
        }

        return $articles;
    }
}

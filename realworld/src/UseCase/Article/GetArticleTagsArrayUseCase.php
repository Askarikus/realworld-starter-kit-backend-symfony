<?php

declare(strict_types=1);

namespace App\UseCase\Article;

class GetArticleTagsArrayUseCase
{
    public function __construct(
        private readonly GetArticleTagsUseCase $getArticleTagsUseCase,
    ) {
    }

    /**
     * @return string[]
     */
    public function execute(string $articleId): array
    {
        $tags = $this->getArticleTagsUseCase->execute($articleId);
        $tagsArray = array_map(fn ($tag) => $tag->getName(), $tags);

        return $tagsArray;
    }
}

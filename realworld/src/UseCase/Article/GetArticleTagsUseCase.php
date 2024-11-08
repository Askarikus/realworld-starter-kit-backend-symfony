<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Tag;
use App\Repository\ArticleTagRepository;
use App\UseCase\Tag\GetTagByIdUseCase;

class GetArticleTagsUseCase
{
    public function __construct(
        private readonly ArticleTagRepository $articleTagRepository,
        private readonly GetTagByIdUseCase $getTagByIdUseCase,
    ) {
    }

    /**
     * @return Tag[]
     */
    public function execute(string $articleId): array
    {
        $articleTags = $this->articleTagRepository->findBy(['articleId' => $articleId]);
        $tags = [];
        foreach ($articleTags as $articleTag) {
            $tags[] = $this->getTagByIdUseCase->execute($articleTag->getTagId());
        }

        return $tags;
    }
}

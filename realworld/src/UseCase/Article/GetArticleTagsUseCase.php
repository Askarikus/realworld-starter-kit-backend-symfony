<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Tag;
use App\UseCase\Tag\GetTagByIdUseCase;
use App\Repository\ArticleTagRepository;

class GetArticleTagsUseCase
{
    public function __construct(
        private readonly ArticleTagRepository $articleTagRepository,
        private readonly GetTagByIdUseCase $getTagByIdUseCase,
    ) {
    }

    /**
     * @param string $articleId
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

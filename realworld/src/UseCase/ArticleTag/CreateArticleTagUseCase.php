<?php

declare(strict_types=1);

namespace App\UseCase\ArticleTag;

use App\Entity\Article;
use App\Entity\ArticleTag;
use App\UseCase\Tag\CreateTagUseCase;
use App\Repository\ArticleTagRepository;
use App\UseCase\Tag\GetTagByNameUseCase;
use App\UseCase\Article\GetArticleByIdUseCase;

class CreateArticleTagUseCase
{
    public function __construct(
        private readonly ArticleTagRepository $articleTagRepository,
        private readonly GetArticleByIdUseCase $getArticleByIdUseCase,
        private readonly GetTagByNameUseCase $getTagByNameUseCase,
        private readonly CreateTagUseCase $createTagUseCase,
    ) {
    }

    public function execute(Article $article, array $tagNames): void
    {
        foreach ($tagNames as $tagName) {
            $tag = $this->getTagByNameUseCase->execute($tagName);

            if (!$tag) {
                $tag = $this->createTagUseCase->execute($tagName);
            }

            $articleTag = new ArticleTag();
            $articleTag->setArticleId($article->getStringId());
            $articleTag->setTagId($tag->getStringId());

            $this->articleTagRepository->save($articleTag);
        }
    }
}

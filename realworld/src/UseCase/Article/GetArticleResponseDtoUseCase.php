<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Dto\Article\ArticleResponseDto;

class GetArticleResponseDtoUseCase
{
    public function __construct(
        private readonly GetArticleTagsArrayUseCase $getArticleTagsArrayUseCase
    ) {
    }

    public function execute(Article $article): ArticleResponseDto
    {
        $articleResponseDto = ArticleResponseDto::fromModel($article);
        $tagsList = $this->getArticleTagsArrayUseCase->execute($article->getStringId());

        if($tagsList) {
            $articleResponseDto->setTagsList($tagsList);
        }

        return $articleResponseDto;

    }

}

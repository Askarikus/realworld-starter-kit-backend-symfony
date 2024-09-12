<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\User;
use App\Entity\Article;
use App\Dto\User\UserResponseDto;
use App\Dto\Article\ArticleResponseDto;

class GetArticleResponseDtoUseCase
{
    public function __construct(
        private readonly GetArticleTagsArrayUseCase $getArticleTagsArrayUseCase,
        private readonly GetArticleLikersUseCase $getArticleLikersUseCase
    ) {
    }

    public function execute(Article $article): ArticleResponseDto
    {
        $articleResponseDto = ArticleResponseDto::fromModel($article);
        $tagsList = $this->getArticleTagsArrayUseCase->execute($article->getStringId());

        if ($tagsList) {
            $articleResponseDto->setTagsList($tagsList);
        }

        /** @var User[] */
        $likers = $this->getArticleLikersUseCase->execute($article->getStringId());
        $likersDtoArray = array_map(fn ($liker) => UserResponseDto::fromModel($liker), $likers);
        $articleResponseDto->setFavoritedBy([]);
        $articleResponseDto->setFavorited(false);
        $articleResponseDto->setFavoritesCount(0);
        if ($likers) {
            $articleResponseDto->setFavoritedBy($likersDtoArray);
            $articleResponseDto->setFavorited(true);
            $articleResponseDto->setFavoritesCount(count($likers));
        }

        return $articleResponseDto;

    }

}

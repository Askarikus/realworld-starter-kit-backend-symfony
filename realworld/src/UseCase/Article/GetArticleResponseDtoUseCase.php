<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\User;
use App\Entity\Article;
use App\Dto\User\UserResponseDto;
use App\Dto\Article\ArticleResponseDto;
use App\UseCase\Like\IsArticleLikedByCurrentUserUseCase;

class GetArticleResponseDtoUseCase
{
    public function __construct(
        private readonly GetArticleTagsArrayUseCase $getArticleTagsArrayUseCase,
        private readonly GetArticleLikersUseCase $getArticleLikersUseCase,
        private readonly GetArticleLikersCountUseCase $getArticleLikersCountUseCase,
        private readonly IsArticleLikedByCurrentUserUseCase $isArticleLikedByCurrentUserUseCase,
    ) {
    }

    public function execute(Article $article, User $user = null): ArticleResponseDto
    {
        $articleResponseDto = ArticleResponseDto::fromModel($article);
        $tagsList = $this->getArticleTagsArrayUseCase->execute($article->getStringId());

        if ($tagsList) {
            $articleResponseDto->setTagsList($tagsList);
        }

        // /** @var User[] */
        // $likers = $this->getArticleLikersUseCase->execute($article->getStringId());
        // is it necessary to grab all users?
        // $likersDtoArray = array_map(fn ($liker) => UserResponseDto::fromModel($liker), $likers);
        // $articleResponseDto->setFavoritedBy([]);
        // if ($likers) {
        //     // $articleResponseDto->setFavoritedBy($likersDtoArray);
        //     $articleResponseDto->setFavorited(true);
        //     $articleResponseDto->setFavoritesCount(count($likers));
        // }

        $articleResponseDto->setFavoritesCount($this->getArticleLikersCountUseCase->execute($article->getStringId()));
        if ($user) {
            $articleResponseDto->setFavoritedByCurrentUser(
                $this->isArticleLikedByCurrentUserUseCase->execute($article, $user)
            );
        }

        return $articleResponseDto;
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Like;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\LikeRepository;

class IsArticleLikedByCurrentUserUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
    ) {
    }

    /**
     * Check if the article is liked by the current user.
     */
    public function execute(Article $article, User $user): bool
    {
        $like = $this->likeRepository->findOneBy([
            'userId' => $user->getStringId(),
            'articleId' => $article->getStringId(),
        ]);

        return null !== $like;
    }
}

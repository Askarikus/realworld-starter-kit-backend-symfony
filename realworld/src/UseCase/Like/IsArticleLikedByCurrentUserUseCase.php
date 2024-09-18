<?php
declare(strict_types=1);

namespace App\UseCase\Like;

use App\Repository\LikeRepository;
use App\Entity\User;
use App\Entity\Article;

class IsArticleLikedByCurrentUserUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository
    ) {
    }

    /**
     * Check if the article is liked by the current user.
     *
     * @param User $user
     * @param Article $article
     * @return bool
     */
    public function execute(Article $article, User $user): bool
    {
        $like = $this->likeRepository->findOneBy([
            'userId' => $user->getStringId(),
            'articleId' => $article->getStringId()
        ]);

        return $like !== null;
    }
}

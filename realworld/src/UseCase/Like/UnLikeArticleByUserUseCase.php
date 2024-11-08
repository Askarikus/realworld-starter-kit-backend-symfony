<?php

declare(strict_types=1);

namespace App\UseCase\Like;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\LikeRepository;

class UnLikeArticleByUserUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
        private readonly ArticleRepository $articleRepository,
    ) {
    }

    public function execute(User $user, Article $article): Article
    {
        $like = $this->likeRepository->findOneBy([
            'userId' => $user->getStringId(),
            'articleId' => $article->getStringId(),
        ]);

        if (null !== $like) {
            $this->likeRepository->delete($like);
        }

        return $article;
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Like;

use App\Entity\User;
use App\Entity\Article;
use App\Repository\LikeRepository;
use App\Repository\ArticleRepository;

class UnLikeArticleByUserUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    public function execute(User $user, Article $article): Article
    {
        $like = $this->likeRepository->findOneBy([
            'userId' => $user->getStringId(),
            'articleId' => $article->getStringId()
        ]);

        if ($like !== null) {
            $this->likeRepository->delete($like);
        }

        return $article;
    }
}

<?php

namespace App\UseCase\Like;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Article;
use App\Repository\LikeRepository;

class LikeArticleByUserUseCase
{

    public function __construct(
        private readonly LikeRepository $likeRepository,
    ) {

    }

    public function execute(User $user, Article $article): Article
    {
        $like = new Like();
        $like->setUserId($user->getStringId());
        $like->setArticleId($article->getStringId());
        $like = $this->likeRepository->save($like);

        return $article;
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\User;
use App\Entity\Article;
use App\Repository\FollowRepository;
use App\Repository\ArticleRepository;
use App\UseCase\Follow\GetCelebsIdsByUserUseCase;

class GetFeedArticlesUseCase
{

    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly FollowRepository $followRepository,
        private readonly GetArticleByUserIdUseCase $getArticleByUserIdUseCase,
        private readonly GetCelebsIdsByUserUseCase $getCelebsIdsByUserUseCase
    ) {
    }

    /**
     *
     * @param User $user
     * @return Article[]
     */
    public function execute(User $user): array
    {
        $celebsIds = $this->getCelebsIdsByUserUseCase->execute($user);

        $celebArticles = [];
        foreach ($celebsIds as $celebId) {
            $articles = $this->getArticleByUserIdUseCase->execute($celebId);
            $celebArticles = array_merge($celebArticles, $articles);
        }

        return $celebArticles;
    }
}

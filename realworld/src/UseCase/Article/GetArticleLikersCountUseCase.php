<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\LikeRepository;
use App\Repository\UserRepository;

class GetArticleLikersCountUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
    ) {
    }

    /**
     * @param string $articleId
     * @return int
     */
    public function execute(string $articleId): int
    {
        $likesCount = $this->likeRepository->count(['articleId' => $articleId]);
        return $likesCount;
    }
}

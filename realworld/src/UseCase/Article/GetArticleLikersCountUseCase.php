<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\LikeRepository;

class GetArticleLikersCountUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
    ) {
    }

    public function execute(string $articleId): int
    {
        $likesCount = $this->likeRepository->count(['articleId' => $articleId]);

        return $likesCount;
    }
}

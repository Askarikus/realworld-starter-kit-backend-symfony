<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Repository\LikeRepository;
use App\Repository\UserRepository;

class GetArticleLikersUseCase
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @return User[]
     */
    public function execute(string $articleId): array
    {
        $likes = $this->likeRepository->findBy(['articleId' => $articleId]);
        $likers = array_map(fn ($like) => $this->userRepository->find($like->getUserId()), $likes);

        return $likers;
    }
}

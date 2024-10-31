<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Repository\FollowRepository;

class IsUserFollowByCelebUseCase
{
    public function __construct(
        private readonly FollowRepository $followRepository,
    ) {
    }

    public function execute(string $followerId, string $celebId): bool
    {
        $follow = $this->followRepository->findOneBy([
            'followerId' => $followerId,
            'celebId' => $celebId,
        ]);

        return null !== $follow;
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Follow;

use App\Repository\FollowRepository;
use App\Entity\User;

class UnfollowUserUseCase
{
    public function __construct(
        private readonly FollowRepository $followRepository,
    ) {
    }

    public function execute(User $follower, User $celeb): void
    {
        $existingFollow = $this->followRepository->findOneBy([
            'followerId' => $follower->getStringId(),
            'celebId' => $celeb->getStringId(),
        ]);

        if ($existingFollow) {
            $this->followRepository->delete($existingFollow);
        }
    }
}

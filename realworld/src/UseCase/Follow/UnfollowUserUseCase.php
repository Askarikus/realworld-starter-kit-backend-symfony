<?php

declare(strict_types=1);

namespace App\UseCase\Follow;

use App\Entity\User;
use App\Repository\FollowRepository;

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

<?php

declare(strict_types=1);

namespace App\UseCase\Follow;

use App\Entity\Follow;
use App\Entity\User;
use App\Repository\FollowRepository;
use Symfony\Component\Security\Core\Security;

class FollowUserUseCase
{
    public function __construct(
        private readonly FollowRepository $followRepository,
        private readonly Security $security,
    ) {
    }

    public function execute(User $follower, User $celeb): void
    {
        $existingFollow = $this->followRepository->findOneBy([
            'followerId' => $follower->getStringId(),
            'celebId' => $celeb->getStringId(),
        ]);

        if ($existingFollow) {
            return;
            // throw new \Exception('You are already following this user');
        }

        $follow = new Follow();
        $follow->setFollowerId($follower->getStringId());
        $follow->setCelebId($celeb->getStringId());

        $this->followRepository->save($follow);
    }
}

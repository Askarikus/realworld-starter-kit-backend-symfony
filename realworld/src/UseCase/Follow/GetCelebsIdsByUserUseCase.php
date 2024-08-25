<?php

declare(strict_types=1);

namespace App\UseCase\Follow;

use App\Entity\User;
use App\Repository\FollowRepository;

class GetCelebsIdsByUserUseCase
{
    public function __construct(
        private readonly FollowRepository $followRepository
    ) {
    }

    /**
     *
     * @param User $user
     * @return string[]
     */
    public function execute(User $user): array
    {
        $follows = $this->followRepository->findBy(['followerId' => $user->getStringId()]);
        $celebsIds = array_map(function ($follow) {
            return $follow->getCelebId();
        }, $follows);
        return $celebsIds;
    }
}

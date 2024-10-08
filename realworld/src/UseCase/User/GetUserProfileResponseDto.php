<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use App\Dto\User\UserProfileResponseDto;

class GetUserProfileResponseDto
{
    public function __construct(
        private readonly IsUserFollowByCelebUseCase $isUserFollowByCelebUseCase,
    ) {
    }

    public function execute(User $userRequested, User $userCeleb): UserProfileResponseDto
    {
        $userProfileResponseDto = UserProfileResponseDto::fromModel($userCeleb);
        $userProfileResponseDto->setIsFollowedByCeleb($this->isUserFollowByCelebUseCase->execute(
            $userRequested->getStringId(),
            $userCeleb->getStringId()
        ));

        return $userProfileResponseDto;
    }
}

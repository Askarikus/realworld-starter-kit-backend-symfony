<?php

declare(strict_types=1);

namespace App\Controller\Follow;

use App\Controller\BaseController;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\User\GetUserByNameUseCase;
use App\UseCase\Follow\UnfollowUserUseCase;
use App\UseCase\User\GetUserProfileResponseDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UnLikeArticleByUserController extends BaseController
{
    public function __construct(
        private readonly UnfollowUserUseCase $unfollowUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly GetUserByNameUseCase $getUserByNameUseCase,
        private readonly GetUserProfileResponseDto $getUserProfileResponseDto,
    ) {
    }

    #[Route(path: 'profiles/{username}/unfollow', name: 'unfollow_user', methods: ['POST'])]
    public function __invoke(string $username)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $user = $this->getAuthUserUseCase->execute();

        $userCeleb = $this->getUserByNameUseCase->execute($username);
        if($userCeleb === null) {
            $this->createErrorResponse(['errors' => ['user' => ['User not found.']]]);
        }

        $this->unfollowUserUseCase->execute(follower: $user, celeb: $userCeleb);
        $userCelebProfileResponseDto = $this->getUserProfileResponseDto->execute($user, $userCeleb);
        return new JsonResponse([
            'profile' => [
                $userCelebProfileResponseDto->jsonSerialize()
            ]
        ]);
    }
}

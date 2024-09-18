<?php

declare(strict_types=1);

namespace App\Controller\Follow;

use App\Controller\BaseController;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\Follow\FollowUserUseCase;
use App\UseCase\User\GetUserByNameUseCase;
use Symfony\Component\HttpFoundation\Response;
use App\UseCase\User\GetUserProfileResponseDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class FollowByUserController extends BaseController
{
    public function __construct(
        private readonly FollowUserUseCase $followUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly GetUserByNameUseCase $getUserByNameUseCase,
        private readonly GetUserProfileResponseDto $getUserProfileResponseDto,
    ) {
    }

    #[Route(path: 'profiles/{username}/follow', name: 'follow_user', methods: ['POST'])]
    public function __invoke(string $username): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getAuthUserUseCase->execute();

        $userCeleb = $this->getUserByNameUseCase->execute($username);

        if ($userCeleb === null) {
            $this->createErrorResponse(['user' => ['User not found.']]);
        }

        $this->followUserUseCase->execute(follower: $user, celeb: $userCeleb);
        $userCelebProfileResponseDto = $this->getUserProfileResponseDto->execute($user, $userCeleb);
        return new JsonResponse(
            data:['profile' =>
                $userCelebProfileResponseDto->jsonSerialize()
            ],
            status: JsonResponse::HTTP_OK
        );

    }
}

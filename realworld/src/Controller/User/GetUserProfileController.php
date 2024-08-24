<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\User\GetUserByNameUseCase;
use Symfony\Component\HttpFoundation\Response;
use App\UseCase\User\GetUserProfileResponseDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserProfileController extends BaseController
{
    public function __construct(
        private readonly GetUserByNameUseCase $getUserByNameUseCase,
        private readonly GetUserProfileResponseDto $getUserProfileResponseDto,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: 'profiles/{username}', name: 'get_user_profile', methods: ['GET'])]
    public function __invoke(string $username): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $user = $this->getAuthUserUseCase->execute();

        $userCeleb = $this->getUserByNameUseCase->execute($username);

        if($userCeleb === null) {
            $this->createErrorResponse(['errors' => ['user' => ['User not found.']]]);
        }

        $userCelebProfileResponseDto = $this->getUserProfileResponseDto->execute($user, $userCeleb);
        return new JsonResponse([
            'profile' => [
                $userCelebProfileResponseDto->jsonSerialize()
            ]
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Dto\User\UserResponseDto;
use App\UseCase\User\GetAuthUserUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCurrenUserController extends BaseController
{
    public function __construct(
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: 'user', name: 'get_current_user', methods: ['GET'])]
    public function __invoke(): Response
    {
        try {
            $user = $this->getAuthUserUseCase->execute();
            $userResponseDto = UserResponseDto::fromModel($user);

            return new JsonResponse([
                'user' => $userResponseDto->jsonSerialize(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }
}

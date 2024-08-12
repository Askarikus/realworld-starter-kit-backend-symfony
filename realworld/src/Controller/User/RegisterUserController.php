<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Dto\User\UserResponseDto;
use App\Helpers\Request\BaseRequest;
use App\Dto\User\RegisterUserRequestDto;
use App\UseCase\User\RegisterUserUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class RegisterUserController extends BaseController
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUserUseCase,
        private readonly JWTTokenManagerInterface $JWTManager,

    ) {
    }
    #[Route(path: 'users', name: 'user_register', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        $requestData = $request->getJsonData()['user'] ?? null;
        if (!$requestData) {
            return $this->createErrorResponse('Malformed Data, user field is required');
        }
        try {
            $registerDataRequestDto = RegisterUserRequestDto::fromArray($requestData);
        } catch (\Exception $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        $user = $this->registerUserUseCase->execute($registerDataRequestDto);

        $userResponseDto = UserResponseDto::fromModel($user);
        $jwtToken = $this->JWTManager->create($user);
        $userResponseDto->setJwtToken($jwtToken);

        return new JsonResponse(
            data: ['user' => $userResponseDto->jsonSerialize()],
            status: Response::HTTP_CREATED,
        );

    }
}

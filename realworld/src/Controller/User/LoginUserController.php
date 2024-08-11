<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Dto\User\UserResponseDto;
use App\Helpers\Request\BaseRequest;
use App\Dto\User\LoginUserRequestDto;
use App\UseCase\User\GetUserByEmailUseCase;
use App\Helpers\Password\PasswordHasherHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\User\IsUserWithEmailExistUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class LoginUserController extends BaseController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $JWTManager,
        private readonly IsUserWithEmailExistUseCase $isUserWithEmailExistUseCase,
        private readonly GetUserByEmailUseCase $getUserByEmailUseCase,
        private readonly PasswordHasherHelper $passwordHasherHelper
    ) {
    }

    #[Route(path: 'users/login', name: 'users_login', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        $requestData = $request->getJsonData()['user'] ?? null;
        if (!$requestData) {
            return $this->createErrorResponse('Malformed Data, user field is required');
        }
        try {
            $loginUserRequestDto = LoginUserRequestDto::fromArray($requestData);
        } catch (\Exception $e) {
            return $this->createErrorResponse($e->getMessage());
        }

        if (!$this->isUserWithEmailExistUseCase->execute($loginUserRequestDto->getEmail())) {
            return $this->createErrorResponse('Invalid credentials');
        }

        $user = $this->getUserByEmailUseCase->execute($loginUserRequestDto->getEmail());
        if (!$this->passwordHasherHelper->verify($user, $loginUserRequestDto->getPassword())) {
            return $this->createErrorResponse('Invalid credentials');
        }

        $jwtToken = $this->JWTManager->create($user);
        $userResponseDto = UserResponseDto::fromModel($user);
        $userResponseDto->setJwtToken($jwtToken);

        return new JsonResponse(
            data: ['user' => $userResponseDto->jsonSerialize()],
            status: Response::HTTP_OK
        );
    }
}

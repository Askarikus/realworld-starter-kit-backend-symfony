<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Dto\User\UserResponseDto;
use App\Controller\BaseController;
use App\Helpers\Request\BaseRequest;
use App\Dto\User\LoginUserRequestDto;
use App\Validator\User\UserLoginValidator;
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

        $validator = new UserLoginValidator();
        if(!$validator->batch()->check($requestData)) {
            return $this->createErrorResponse($validator->batch()->getError());
        }

        $loginUserRequestDto = LoginUserRequestDto::fromArray($requestData);

        $user = $this->getUserByEmailUseCase->execute($loginUserRequestDto->getEmail());
        if(!$user) {
            return $this->createErrorResponse(['user' => ['User not found.']]);
        }
        if (!$this->passwordHasherHelper->verify($user, $loginUserRequestDto->getPassword())) {
            return $this->createErrorResponse(['user' => ['Invalid credentials.']]);
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

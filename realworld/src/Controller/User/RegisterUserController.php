<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Dto\User\UserResponseDto;
use App\Controller\BaseController;
use App\Helpers\Request\BaseRequest;
use App\Dto\User\RegisterUserRequestDto;
use App\Language\Language;
use App\UseCase\User\RegisterUserUseCase;
use App\Validator\User\UserRegisterValidator;
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

        $validator = new UserRegisterValidator();

        if(!$validator->batch()->check($requestData)){
            return $this->createErrorResponse($validator->batch()->getError());
        }

        $registerDataRequestDto = RegisterUserRequestDto::fromArray($requestData);

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

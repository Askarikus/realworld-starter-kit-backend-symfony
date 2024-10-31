<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Dto\User\EditUserRequestDto;
use App\Dto\User\UserResponseDto;
use App\Helpers\Request\BaseRequest;
use App\UseCase\User\EditUserUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use App\Validator\User\UserUpdateValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends BaseController
{
    public function __construct(
        private readonly EditUserUseCase $editUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: '/user', name: 'edit_user', methods: ['PUT'])]
    public function __invoke(BaseRequest $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $requestData = $request->getJsonData()['user'] ?? null;

        $validator = new UserUpdateValidator();

        if (!$validator->batch()->check($requestData)) {
            return $this->createErrorResponse($validator->batch()->getError());
        }

        $editUserRequestDto = EditUserRequestDto::fromArray($requestData);

        $user = $this->getAuthUserUseCase->execute();
        try {
            $user = $this->editUserUseCase->execute($user->getStringId(), $editUserRequestDto);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $userResponseDto = UserResponseDto::fromModel($user);

        return new JsonResponse(['user' => $userResponseDto->jsonSerialize()], Response::HTTP_OK);
    }
}

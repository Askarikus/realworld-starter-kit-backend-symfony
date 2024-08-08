<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Helpers\Request\BaseRequest;
use App\Dto\User\RegisterUserRequestDto;
use App\UseCase\User\RegisterUserUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterUserController extends AbstractController
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUserUseCase
    ) {

    }
    #[Route(path: 'users', name: 'user_register', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        /** @var User */
        $user = null;
        $registerDataRequestDto = RegisterUserRequestDto::fromArray($request->getJsonData()['user']);

        $user = $this->registerUserUseCase->execute($registerDataRequestDto);

        return new JsonResponse(
            data: ['user' => ['id' => $user->getId(), 'name' => $user->getName()]],
            status: Response::HTTP_CREATED,
            // headers: $headers
        );

    }

}

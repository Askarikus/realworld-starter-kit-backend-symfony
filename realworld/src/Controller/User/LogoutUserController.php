<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutUserController extends BaseController
{
    #[Route(path: 'users/logout', name: 'users_logout', methods: ['POST'])]
    public function __invoke(): Response
    {
        return new JsonResponse(
            data: ['message' => 'Successfully logged out.'],
            status: Response::HTTP_OK
        );
    }
}

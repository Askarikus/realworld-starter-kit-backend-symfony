<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    protected function createErrorResponse(array $errors): JsonResponse
    {
        return new JsonResponse(
            data: ['errors' => $errors],
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

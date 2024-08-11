<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected function createErrorResponse(string $message): JsonResponse
    {
        return new JsonResponse(
            data: ['message' => $message],
            status: Response::HTTP_BAD_REQUEST
        );
    }
}

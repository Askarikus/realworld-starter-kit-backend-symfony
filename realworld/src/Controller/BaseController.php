<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     *
     * @param array $message
     * @return JsonResponse
     */
    protected function createErrorResponse(array $errors): JsonResponse
    {
        return new JsonResponse(
            data: ['errors' => $errors],
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}

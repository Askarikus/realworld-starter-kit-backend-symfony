<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\BaseController;
use App\UseCase\Article\DeleteArticleUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteArticleController extends BaseController
{
    public function __construct(
        private readonly DeleteArticleUseCase $deleteArticleUseCase,
    ) {
    }

    #[Route(path: 'articles/{id}', name: 'article_delete', methods: ['DELETE'])]
    public function __invoke(int $id): Response
    {
        $result = $this->deleteArticleUseCase->execute($id);

        if (!$result) {
            return new JsonResponse(
                data: ['error' => 'Article not found or could not be deleted.'],
                status: Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse(
            data: ['message' => 'Article deleted successfully.'],
            status: Response::HTTP_OK
        );
    }
}

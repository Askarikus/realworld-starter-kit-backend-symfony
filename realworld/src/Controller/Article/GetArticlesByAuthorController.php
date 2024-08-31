<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Helpers\Request\BaseRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use App\UseCase\Article\GetArticlesByAuthorNameUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetArticlesByAuthorController extends AbstractController
{
    public function __construct(
        private readonly GetArticlesByAuthorNameUseCase $getArticlesByAuthorNameUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase
    ) {

    }

    #[Route(path: 'articles', name: 'articles_by_author', methods: ['GET'], priority: 2)]
    public function __invoke(BaseRequest $request): Response
    {
        $authorName = $request->getRequest()->get('author');
        if($authorName) {
            try {
                $articles = $this->getArticlesByAuthorNameUseCase->execute($authorName);
            } catch (NotFoundHttpException $e) {
                return new JsonResponse(
                    data: ['message' => 'Author not found'],
                    status: Response::HTTP_NOT_FOUND,
                );
            }
        }

        if (empty($articles)) {
            return new JsonResponse(
                data: ['message' => 'No articles found for this author'],
                status: Response::HTTP_NOT_FOUND,
            );
        }

        $articlesResponseDto = array_map(fn ($article) => $this->getArticleResponseDtoUseCase->execute($article), $articles);

        return new JsonResponse(
            data: ['articles' => $articlesResponseDto],
            status: Response::HTTP_OK,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Helpers\Request\BaseRequest;
use App\UseCase\Article\GetAllArticlesUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use App\UseCase\Article\GetArticlesByAuthorNameUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetArticlesController extends AbstractController
{
    public function __construct(
        private readonly GetAllArticlesUseCase $getAllArticlesUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
        private readonly GetArticlesByAuthorNameUseCase $getArticlesByAuthorNameUseCase,
    ) {

    }

    #[Route(path: 'articles', name: 'articles_get', methods: ['GET'])]
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
        } else {
            $articles = $this->getAllArticlesUseCase->execute();
        }

        $articlesResponseDto = array_map(fn ($article) => $this->getArticleResponseDtoUseCase->execute($article), $articles);

        return new JsonResponse(
            data: [
                'articles' => $articlesResponseDto,
                'articlesCount' => count($articles)
                ],
            status: Response::HTTP_OK,
        );
    }
}

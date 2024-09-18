<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Helpers\Request\BaseRequest;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\Article\GetAllArticlesUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Article\GetArticlesByTagUseCase;
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
        private readonly GetArticlesByTagUseCase $getArticlesByTagUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase
    ) {

    }

    #[Route(path: 'articles', name: 'articles_get', methods: ['GET'])]
    public function __invoke(BaseRequest $request): Response
    {
        $authorName = $request->getRequest()->get('author');
        $tag = $request->getRequest()->get('tag');
        if ($authorName) {
            try {
                $articles = $this->getArticlesByAuthorNameUseCase->execute($authorName);
            } catch (NotFoundHttpException $e) {
                return new JsonResponse(
                    data: ['message' => 'Author not found'],
                    status: Response::HTTP_NOT_FOUND,
                );
            }
        } elseif ($tag) {
            $articles = $this->getArticlesByTagUseCase->execute($tag);
        } else {
            $articles = $this->getAllArticlesUseCase->execute();
        }

        try {
            $user = $this->getAuthUserUseCase->execute();
            $articlesResponseDto = array_map(
                fn ($article) => $this->getArticleResponseDtoUseCase->execute($article, $user),
                $articles
            );
        } catch (\Exception $e) {
            $articlesResponseDto = array_map(
                fn ($article) => $this->getArticleResponseDtoUseCase->execute($article),
                $articles
            );
        }

        return new JsonResponse(
            data: [
                'articles' => $articlesResponseDto,
                'articlesCount' => count($articles)
                ],
            status: Response::HTTP_OK,
        );
    }
}

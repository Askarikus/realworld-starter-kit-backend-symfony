<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Dto\Article\ArticleResponseDto;
use App\UseCase\Article\GetArticleBySlugUseCase;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetArticleBySlugController extends AbstractController
{
    public function __construct(
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase
    ) {

    }

    #[Route(path: 'articles/{:slug}', name: 'article_by_slug', methods: ['GET'], priority: 2)]
    public function __invoke(string $slug): Response
    {
        $article = $this->getArticleBySlugUseCase->execute($slug);

        if (!$article) {
            return new JsonResponse(
                data: ['message' => 'Article not found'],
                status: Response::HTTP_NOT_FOUND,
            );
        }

        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);

        return new JsonResponse(
            data: ['article' => $articleResponseDto],
            status: Response::HTTP_OK,
        );
    }
}

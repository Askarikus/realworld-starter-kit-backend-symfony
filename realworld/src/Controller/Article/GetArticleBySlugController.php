<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\UseCase\Article\GetArticleBySlugUseCase;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetArticleBySlugController extends AbstractController
{
    public function __construct(
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: 'articles/{slug}', name: 'article_by_slug', methods: ['GET'])]
    public function __invoke(string $slug): Response
    {
        $article = $this->getArticleBySlugUseCase->execute($slug);

        if (!$article) {
            return new JsonResponse(
                data: ['message' => 'Article not found'],
                status: Response::HTTP_NOT_FOUND,
            );
        }
        try {
            $user = $this->getAuthUserUseCase->execute();
            $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article, $user);
        } catch (\Exception $e) {
            $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);
        }

        return new JsonResponse(
            data: ['article' => $articleResponseDto],
            status: Response::HTTP_OK,
        );
    }
}

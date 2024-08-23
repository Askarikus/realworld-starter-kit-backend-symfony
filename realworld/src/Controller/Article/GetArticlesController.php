<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Dto\Article\ArticleResponseDto;
use App\UseCase\Article\GetAllArticlesUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetArticlesController extends AbstractController
{
    public function __construct(
        private readonly GetAllArticlesUseCase $getAllArticlesUseCase
    ) {

    }

    #[Route(path: 'articles', name: 'article_get_all', methods: ['GET'])]
    public function __invoke(): Response
    {

        $articles = $this->getAllArticlesUseCase->execute();

        $articlesResponseDto = array_map(fn ($article) => ArticleResponseDto::fromModel($article), $articles);

        return new JsonResponse(
            data: [
                'articles' => $articlesResponseDto,
                'articlesCount' => count($articles)
                ],
            status: Response::HTTP_OK,
        );
    }
}

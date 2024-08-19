<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Dto\Article\ArticleResponseDto;
use App\Helpers\Request\BaseRequest;
use App\Dto\Article\CreateArticleRequestDto;
use App\UseCase\Article\CreateArticleUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateArticleController extends AbstractController
{
    public function __construct(
        private readonly CreateArticleUseCase $createArticleUseCase
    )
    {

    }

    #[Route(path: 'articles', name: 'article_create', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        $requestData = $request->getJsonData()['article'] ?? null;

        $createArticleRequestDto = CreateArticleRequestDto::fromArray($requestData);

        $article = $this->createArticleUseCase->execute($createArticleRequestDto);
        $articleResponseDto = ArticleResponseDto::fromModel($article);

        return new JsonResponse(
            data: ['article' => $articleResponseDto->jsonSerialize()],
            status: Response::HTTP_CREATED,
        );
    }
}

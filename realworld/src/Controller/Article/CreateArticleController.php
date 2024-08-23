<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Helpers\Request\BaseRequest;
use App\Dto\Article\ArticleResponseDto;
use App\Dto\Article\CreateArticleRequestDto;
use App\UseCase\Article\CreateArticleUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleTagsArrayUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateArticleController extends AbstractController
{
    public function __construct(
        private readonly CreateArticleUseCase $createArticleUseCase,
        private readonly GetArticleTagsArrayUseCase $getArticleTagsArrayUseCase
    ) {
    }

    #[Route(path: 'articles', name: 'article_create', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        $requestData = $request->getJsonData()['article'] ?? null;

        $createArticleRequestDto = CreateArticleRequestDto::fromArray($requestData);

        $article = $this->createArticleUseCase->execute($createArticleRequestDto);
        $articleResponseDto = ArticleResponseDto::fromModel($article);
        if($createArticleRequestDto->getTagList()) {
            $tagsList = $this->getArticleTagsArrayUseCase->execute($article->getStringId());
            $articleResponseDto->setTagsList($tagsList);
        }

        return new JsonResponse(
            data: ['article' => $articleResponseDto->jsonSerialize()],
            status: Response::HTTP_CREATED,
        );
    }
}

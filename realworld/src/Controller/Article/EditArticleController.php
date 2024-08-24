<?php

namespace App\Controller\Article;

use App\Controller\BaseController;
use App\Helpers\Request\BaseRequest;
use App\UseCase\Article\EditArticleUseCase;
use App\Dto\Article\CreateArticleRequestDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditArticleController extends BaseController
{
    public function __construct(
        private readonly EditArticleUseCase $editArticleUseCase,

        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase
    ) {
    }

    #[Route(path: 'articles/{slug}', name: 'article_edit', methods: ['PUT'])]
    public function __invoke(string $slug, BaseRequest $request): JsonResponse
    {
        $requestData = $request->getJsonData()['article'] ?? null;
        $createArticleRequestDto = CreateArticleRequestDto::fromArray($requestData);
        try {
            $article = $this->editArticleUseCase->execute($slug, $createArticleRequestDto);
        } catch (NotFoundHttpException $e) {
            return $this->createErrorResponse(['article' => ['Article not found']]);
        }
        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);

        return new JsonResponse(
            data: ['article' => $articleResponseDto->jsonSerialize()],
            status: JsonResponse::HTTP_OK
        );
    }
}

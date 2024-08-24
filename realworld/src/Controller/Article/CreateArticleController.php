<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\BaseController;
use App\Helpers\Request\BaseRequest;
use App\Dto\Article\CreateArticleRequestDto;
use App\UseCase\Article\CreateArticleUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Validator\Article\CreateArticleValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;

class CreateArticleController extends BaseController
{
    public function __construct(
        private readonly CreateArticleUseCase $createArticleUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase
    ) {
    }

    #[Route(path: 'articles', name: 'article_create', methods: ['POST'])]
    public function __invoke(BaseRequest $request): Response
    {
        $requestData = $request->getJsonData()['article'] ?? null;

        $validator = new CreateArticleValidator();

        if(!$validator->batch()->check($requestData)) {
            return $this->createErrorResponse($validator->batch()->getError());
        }

        $createArticleRequestDto = CreateArticleRequestDto::fromArray($requestData);

        $article = $this->createArticleUseCase->execute($createArticleRequestDto);
        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);

        return new JsonResponse(
            data: ['article' => $articleResponseDto->jsonSerialize()],
            status: Response::HTTP_CREATED,
        );
    }
}

<?php

namespace App\Controller\Article;

use App\Controller\BaseController;
use App\Helpers\Request\BaseRequest;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\Article\EditArticleUseCase;
use App\Dto\Article\CreateArticleRequestDto;
use Symfony\Component\Routing\Annotation\Route;
use App\Validator\Article\CreateArticleValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditArticleController extends BaseController
{
    public function __construct(
        private readonly EditArticleUseCase $editArticleUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase
    ) {
    }

    #[Route(path: 'articles/{slug}', name: 'article_edit', methods: ['PUT'])]
    public function __invoke(string $slug, BaseRequest $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $requestData = $request->getJsonData()['article'] ?? null;

        $validator = new CreateArticleValidator();

        if(!$validator->batch()->check($requestData)) {
            return $this->createErrorResponse($validator->batch()->getError());
        }

        $createArticleRequestDto = CreateArticleRequestDto::fromArray($requestData);
        try {
            $article = $this->editArticleUseCase->execute($slug, $createArticleRequestDto);
        } catch (NotFoundHttpException $e) {
            return $this->createErrorResponse(['article' => ['Article not found']]);
        }
        $user = $this->getAuthUserUseCase->execute();
        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article, $user);

        return new JsonResponse(
            data: ['article' => $articleResponseDto->jsonSerialize()],
            status: JsonResponse::HTTP_OK
        );
    }
}

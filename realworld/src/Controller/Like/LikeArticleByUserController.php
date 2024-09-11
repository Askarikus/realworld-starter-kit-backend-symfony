<?php

declare(strict_types=1);

namespace App\Controller\Like;

use App\Controller\BaseController;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\Like\LikeArticleByUserUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Article\GetArticleBySlugUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;

class LikeArticleByUserController extends BaseController
{
    public function __construct(
        private readonly LikeArticleByUserUseCase $likeArticleByUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,

    ) {
    }

    #[Route(path: 'articles/{slug}/favorite', name: 'like_article', methods: ['POST'])]
    public function __invoke(string $slug): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getAuthUserUseCase->execute();

        $article = $this->getArticleBySlugUseCase->execute($slug);

        if ($article === null) {
            $this->createErrorResponse(['errors' => ['article' => ['Article not found.']]]);
        }

        $article = $this->likeArticleByUserUseCase->execute(user: $user, article: $article);
        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);
        return new JsonResponse([
            'article' => [
                $articleResponseDto->jsonSerialize()
            ]
        ]);
    }
}

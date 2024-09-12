<?php
declare(strict_types=1);

namespace App\Controller\Like;

use App\Controller\BaseController;
use App\UseCase\User\GetAuthUserUseCase;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\Article\GetArticleBySlugUseCase;
use App\UseCase\Like\UnLikeArticleByUserUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UseCase\Article\GetArticleResponseDtoUseCase;



class UnLikeArticleByUserController extends BaseController
{
    public function __construct(
        private readonly UnLikeArticleByUserUseCase $unLikeArticleByUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
    ) {
    }

    #[Route(path: 'articles/{slug}/unfavorite', name: 'unlike_article', methods: ['DELETE'])]
    public function __invoke(string $slug): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $user = $this->getAuthUserUseCase->execute();
        $article = $this->getArticleBySlugUseCase->execute($slug);

        if ($article === null) {
            return $this->createErrorResponse(['article' => ['Article not found.']]);
        }

        $this->unLikeArticleByUserUseCase->execute(user: $user, article: $article);
        $articleResponseDto = $this->getArticleResponseDtoUseCase->execute($article);

        return new JsonResponse([
            'article' => [
                $articleResponseDto->jsonSerialize()
            ]
        ]);
    }
}

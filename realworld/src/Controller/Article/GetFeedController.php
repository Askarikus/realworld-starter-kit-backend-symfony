<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\BaseController;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use App\UseCase\Article\GetFeedArticlesUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetFeedController extends BaseController
{
    public function __construct(
        private readonly GetFeedArticlesUseCase $getFeedArticlesUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: 'articles/feed', name: 'articles_get_feed', methods: ['GET'], priority: 1)]
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getAuthUserUseCase->execute();
        $feed = $this->getFeedArticlesUseCase->execute($user);

        $feedResponseDto = array_map(fn ($article) => $this->getArticleResponseDtoUseCase->execute($article, $user), $feed);

        return new JsonResponse(
            data: [
                'articles' => $feedResponseDto,
                'articlesCount' => count($feed),
            ],
            status: Response::HTTP_OK,
        );
    }
}

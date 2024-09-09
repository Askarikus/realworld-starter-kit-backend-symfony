<?php

declare(strict_types=1);

namespace App\Controller\Follow;

use App\Controller\BaseController;
use App\UseCase\Article\GetArticleBySlugUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use App\UseCase\Follow\FollowUserUseCase;
use App\UseCase\User\GetUserByNameUseCase;
use Symfony\Component\HttpFoundation\Response;
use App\UseCase\User\GetUserProfileResponseDto;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class LikeArticleByUserController extends BaseController
{
    public function __construct(
        private readonly FollowUserUseCase $followUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly GetUserByNameUseCase $getUserByNameUseCase,
        private readonly GetUserProfileResponseDto $getUserProfileResponseDto,
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase
    ) {
    }

    #[Route(path: 'articles/{slug}/favorite', name: 'like_article', methods: ['POST'])]
    public function __invoke(string $slug): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getAuthUserUseCase->execute();

        $article = $this->getArticleBySlugUseCase->execute($slug);

        if($article === null) {
            $this->createErrorResponse(['errors' => ['article' => ['Article not found.']]]);
        }

        // $this->likeArticleByUserUseCase->execute(follower: $user, celeb: $userCeleb);
        // $userCelebProfileResponseDto = $this->getUserProfileResponseDto->execute($user, $userCeleb);
        return new JsonResponse([
            'profile' => [
                // $userCelebProfileResponseDto->jsonSerialize()
            ]
        ]);

    }
}

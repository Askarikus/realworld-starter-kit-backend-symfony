<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Entity\Article;
use App\Helpers\Request\BaseRequest;
use App\UseCase\Article\GetAllArticlesUseCase;
use App\UseCase\Article\GetArticleResponseDtoUseCase;
use App\UseCase\Article\GetArticlesByAuthorNameUseCase;
use App\UseCase\Article\GetArticlesByTagUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GetArticlesController extends AbstractController
{
    public function __construct(
        private readonly GetAllArticlesUseCase $getAllArticlesUseCase,
        private readonly GetArticleResponseDtoUseCase $getArticleResponseDtoUseCase,
        private readonly GetArticlesByAuthorNameUseCase $getArticlesByAuthorNameUseCase,
        private readonly GetArticlesByTagUseCase $getArticlesByTagUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {
    }

    #[Route(path: 'articles', name: 'articles_get', methods: ['GET'])]
    public function __invoke(BaseRequest $request): Response
    {
        $authorName = $request->getRequest()->get('author');
        $tag = $request->getRequest()->get('tag');

        $page = intval($request->getRequest()->get('page', 1));
        $limit = intval($request->getRequest()->get('limit', 10));

        if ($authorName) {
            try {
                $articles = $this->getArticlesByAuthorNameUseCase->execute($authorName);
            } catch (NotFoundHttpException $e) {
                return new JsonResponse(
                    data: ['message' => 'Author not found'],
                    status: Response::HTTP_NOT_FOUND,
                );
            }
        } elseif ($tag) {
            $articles = $this->getArticlesByTagUseCase->execute($tag);
        } else {
            $articles = $this->getAllArticlesUseCase->execute();
        }

        $arrayAdapterFanta = new ArrayAdapter($articles);
        $pagerfanta = new Pagerfanta($arrayAdapterFanta);

        $pagerfanta->setMaxPerPage($limit); // 10 by default
        $pagerfanta->setCurrentPage($page); // 1 by default

        $nbResults = $pagerfanta->getNbResults();

        /** @var Article[] */
        $articles = $pagerfanta->getCurrentPageResults();

        try {
            $user = $this->getAuthUserUseCase->execute();
            $articlesResponseDto = array_map(
                fn ($article) => $this->getArticleResponseDtoUseCase->execute($article, $user),
                $articles
            );
        } catch (\Exception $e) {
            $articlesResponseDto = array_map(
                fn ($article) => $this->getArticleResponseDtoUseCase->execute($article),
                $articles
            );
        }

        return new JsonResponse(
            data: [
                'articles' => $articlesResponseDto,
                'articlesCount' => $nbResults ?? count($articles),
            ],
            status: Response::HTTP_OK,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\UseCase\User\GetUserByNameUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetArticlesByAuthorNameUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly GetUserByNameUseCase $getUserByNameUseCase,
    ) {
    }

    /**
     * @return Article[]|null
     *
     * @throws NotFoundHttpException
     */
    public function execute(string $authorName): ?array
    {
        $author = $this->getUserByNameUseCase->execute($authorName);
        if (null === $author) {
            throw new NotFoundHttpException('Author not found');
        }

        return $this->articleRepository->findBy(['author' => $author]);
    }
}

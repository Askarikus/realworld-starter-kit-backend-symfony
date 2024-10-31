<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Dto\Article\CreateArticleRequestDto;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\UseCase\ArticleTag\CreateArticleTagUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditArticleUseCase
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly GetArticleBySlugUseCase $getArticleBySlugUseCase,
        private readonly CreateArticleTagUseCase $createArticleTagUseCase,
    ) {
    }

    public function execute(
        string $slug,
        CreateArticleRequestDto $createArticleRequestDto,
    ): ?Article {
        $article = $this->getArticleBySlugUseCase->execute($slug);

        if (!$article) {
            throw new NotFoundHttpException('Article not found');
        }

        $fields = ['title', 'description', 'body'];
        foreach ($fields as $field) {
            if (method_exists($createArticleRequestDto, 'get'.ucfirst($field))) {
                if ($createArticleRequestDto->{'get'.ucfirst($field)}()) {
                    if (method_exists($article, 'set'.ucfirst($field))) {
                        $article->{'set'.ucfirst($field)}($createArticleRequestDto->{'get'.ucfirst($field)}());
                    }
                }
            }
        }
        if ($createArticleRequestDto->getTagList()) {
            $this->createArticleTagUseCase->execute($article, $createArticleRequestDto->getTagList());
        }

        $article = $this->articleRepository->save($article);

        return $article;
    }
}

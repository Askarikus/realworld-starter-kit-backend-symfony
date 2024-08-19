<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\UseCase\User\GetAuthUserUseCase;
use App\Dto\Article\CreateArticleRequestDto;
use App\Helpers\Slug\GenerateUniqueSlugTrait;

class CreateArticleUseCase
{

    use GenerateUniqueSlugTrait;

    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly GetAuthUserUseCase $getAuthUserUseCase
    ) {
    }

    public function getRepository()
    {
        return $this->articleRepository;
    }

    public function execute(CreateArticleRequestDto $createArticleRequestDto)
    {
        $article = new Article();
        $article->setSlug($this->generateUniqueSlug($createArticleRequestDto->getTitle()));
        $article->setTitle($createArticleRequestDto->getTitle());
        $article->setDescription($createArticleRequestDto->getDescription());
        $article->setBody($createArticleRequestDto->getBody());
        $article->setAuthor($this->getAuthUserUseCase->execute());
        $this->articleRepository->save($article);

        return $article;
    }
}

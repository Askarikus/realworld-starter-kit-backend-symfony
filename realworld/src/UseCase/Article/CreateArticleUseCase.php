<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\UseCase\User\GetAuthUserUseCase;
use App\Dto\Article\CreateArticleRequestDto;
use App\Helpers\Slug\GenerateUniqueSlugTrait;
use App\UseCase\ArticleTag\CreateArticleTagUseCase;

class CreateArticleUseCase
{

    use GenerateUniqueSlugTrait;

    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
        private readonly CreateArticleTagUseCase $createArticleTagUseCase,
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
        $article = $this->articleRepository->save($article);
        if ($createArticleRequestDto->getTagList()) {
            $this->createArticleTagUseCase->execute($article, $createArticleRequestDto->getTagList());
        }

        return $article;
    }
}

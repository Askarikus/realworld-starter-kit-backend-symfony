<?php

declare(strict_types=1);

namespace App\UseCase\Article;

use App\Dto\Article\CreateArticleRequestDto;
use App\Entity\Article;
use App\Helpers\Slug\GenerateUniqueSlugTrait;
use App\Repository\ArticleRepository;
use App\UseCase\ArticleTag\CreateArticleTagUseCase;
use App\UseCase\User\GetAuthUserUseCase;

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

        $article->setSlug($this->generateUniqueSlug($createArticleRequestDto->getTitle()));
        $article->setAuthor($this->getAuthUserUseCase->execute());

        $article = $this->articleRepository->save($article);

        if ($createArticleRequestDto->getTagList()) {
            $this->createArticleTagUseCase->execute($article, $createArticleRequestDto->getTagList());
        }

        return $article;
    }
}

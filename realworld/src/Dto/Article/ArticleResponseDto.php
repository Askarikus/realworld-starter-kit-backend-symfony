<?php

declare(strict_types=1);

namespace App\Dto\Article;

use App\Entity\Article;
use App\Entity\AbstractEntity;
use App\Dto\AbstractResponseDto;
use App\Dto\User\UserResponseDto;
use App\Helpers\Parser\ParseDtoTrait;

final class ArticleResponseDto extends AbstractResponseDto
{
    use ParseDtoTrait;

    public function __construct(
        private readonly string $slug,
        private readonly string $title,
        private readonly string $description,
        private readonly string $body,
        private readonly UserResponseDto $author
    ) {

    }

    public static function fromModel(Article|AbstractEntity $article): static
    {
        return new static(
            slug: $article->getSlug(),
            title:$article->getTitle(),
            description: $article->getDescription(),
            body: $article->getBody(),
            author: self::parseResponseDto(UserResponseDto::class, $article->getAuthor())
        );
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAuthor(): UserResponseDto
    {
        return $this->author;
    }
    public function jsonSerialize(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'author' => $this->author,
        ];
    }
}

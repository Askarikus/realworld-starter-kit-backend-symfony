<?php

declare(strict_types=1);

namespace App\Dto\Article;

use App\Dto\AbstractResponseDto;
use App\Dto\User\UserResponseDto;
use App\Entity\AbstractEntity;
use App\Entity\Article;
use App\Helpers\Parser\ParseDtoTrait;

final class ArticleResponseDto extends AbstractResponseDto
{
    use ParseDtoTrait;

    private ?array $tagList = [];
    private bool $favoritedByCurrentUser = false;
    private int $favoritesCount = 0;

    public function __construct(
        private readonly string $slug,
        private readonly string $title,
        private readonly string $description,
        private readonly string $body,
        private readonly UserResponseDto $author,
        private readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromModel(Article|AbstractEntity $article): static
    {
        return new static(
            slug: $article->getSlug(),
            title: $article->getTitle(),
            description: $article->getDescription(),
            body: $article->getBody(),
            author: self::parseResponseDto(UserResponseDto::class, $article->getAuthor()),
            createdAt: $article->getCreatedAt(),
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

    public function getCreatedAt()
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }

    public function getTagsList()
    {
        return $this->tagList;
    }

    public function setTagsList(array $tagsList): void
    {
        $this->tagList = $tagsList;
    }

    public function getFavoritedByCurrentUser(): bool
    {
        return $this->favoritedByCurrentUser;
    }

    public function setFavoritedByCurrentUser(bool $favoritedByCurrentUser): void
    {
        $this->favoritedByCurrentUser = $favoritedByCurrentUser;
    }

    public function getFavoritesCount(): int
    {
        return $this->favoritesCount;
    }

    public function setFavoritesCount(int $favoritesCount): void
    {
        $this->favoritesCount = $favoritesCount;
    }

    public function jsonSerialize(): array
    {
        return [
            'slug' => $this->getSlug(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'body' => $this->getBody(),
            'author' => $this->getAuthor(),
            'tagList' => $this->getTagsList(),
            'favoritedByCurrentUser' => $this->getFavoritedByCurrentUser(),
            'favoritesCount' => $this->getFavoritesCount(),
            'createdAt' => $this->getCreatedAt(),
        ];
    }
}

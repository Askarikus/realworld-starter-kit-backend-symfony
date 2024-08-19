<?php

declare(strict_types=1);

namespace App\Dto\Article;

use App\Dto\AbstractRequestDto;
use App\Helpers\Parser\ParseDataTrait;

final class CreateArticleRequestDto extends AbstractRequestDto
{
    use ParseDataTrait;

    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly string $body,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            title:self::parseString($data['title']),
            description:self::parseString($data['description']),
            body: self::parseString($data['body']),
        );
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

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
        ];
    }

}

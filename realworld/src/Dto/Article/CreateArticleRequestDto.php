<?php

declare(strict_types=1);

namespace App\Dto\Article;

use App\Dto\AbstractRequestDto;
use App\Helpers\Parser\ParseDataTrait;

final class CreateArticleRequestDto extends AbstractRequestDto
{
    use ParseDataTrait;

    public function __construct(
        private readonly ?string $title,
        private readonly ?string $description,
        private readonly ?string $body,
        private readonly ?array $tagList,
    ) {

    }

    public static function fromArray(array $data): static
    {
        return new static(
            title:self::parseNullableString($data['title']),
            description:self::parseNullableString($data['description']),
            body: self::parseNullableString($data['body']),
            tagList: self::parseNullableArray($data['tagList']),
        );
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
    public function getTagList(): ?array
    {
        return $this->tagList;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'tag_list' => $this->tagList,
        ];
    }

}

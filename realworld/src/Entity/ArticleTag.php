<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleTagRepository::class)]
#[ORM\Table(name: 'article_tag')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(
    name: 'article_tag_unique_idx',
    columns: ['article_id', 'tag_id']
)]
class ArticleTag extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $articleId;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $tagId;

    public function getArticleId(): string
    {
        return $this->articleId;
    }

    public function setArticleId($articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getTagId(): string
    {
        return $this->tagId;
    }

    public function setTagId($tagId): void
    {
        $this->tagId = $tagId;
    }

    public function getJson(): array
    {
        return [];
    }
}

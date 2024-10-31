<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: 'like_table')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(
    name: 'user_article_unique_idx',
    columns: ['user_id', 'article_id']
)]
class Like extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $userId;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $articleId;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getArticleId(): string
    {
        return $this->articleId;
    }

    public function setArticleId(string $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getJson(): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'article')]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Article extends AbstractEntity
{
    #[ORM\Column(type: 'string', unique: false)]
    private string $slug;

    #[ORM\Column(type: 'string', unique: false)]
    private string $title;

    #[ORM\Column(type: 'string', unique: false)]
    private string $description;

    #[ORM\Column(type: 'string', unique: false)]
    private string $body;

    #[ORM\ManyToOne(inversedBy: 'articles', targetEntity: User::class)]
    private User $author;

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getJson(): array
    {
        return [];
    }
}

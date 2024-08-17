<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;

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

    #[ORM\ManyToOne(inversedBy: 'articles', targetEntity: User::class)]
    private User $author;

    public function getJson(): array
    {
        return [];
    }
}

<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
#[ORM\Table(name: 'follow')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(
    name: 'follower_celeb_unique_idx',
    columns: ['follower_id', 'celeb_id']
)]
class Follow extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $followerId;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $celebId;

    public function getFollowerId(): string
    {
        return $this->followerId;
    }

    public function setFollowerId($followerId): void
    {
        $this->followerId = $followerId;
    }

    public function getCelebId(): string
    {
        return $this->celebId;
    }

    public function setCelebId($celebId): void
    {
        $this->celebId = $celebId;
    }

    public function getJson(): array
    {
        return [];
    }
}

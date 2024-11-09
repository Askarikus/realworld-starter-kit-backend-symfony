<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UUidV7;

#[ORM\HasLifecycleCallbacks]
abstract class AbstractEntity
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true, nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    protected UUidV7 $id;

    public function getId(): UUidV7
    {
        return $this->id;
    }

    public function getStringId(): string
    {
        return (string) $this->getId();
    }

    public function setId(UUidV7 $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed[]
     */
    abstract public function getJson(): array;
}

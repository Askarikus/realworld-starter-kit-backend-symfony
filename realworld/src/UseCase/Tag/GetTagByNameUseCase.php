<?php

declare(strict_types=1);

namespace App\UseCase\Tag;

use App\Repository\TagRepository;
use App\Entity\Tag;

class GetTagByNameUseCase
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function execute(string $name): ?Tag
    {
        return $this->tagRepository->findOneBy(['name' => $name]);
    }
}

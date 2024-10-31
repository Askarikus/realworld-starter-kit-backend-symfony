<?php

declare(strict_types=1);

namespace App\UseCase\Tag;

use App\Entity\Tag;
use App\Repository\TagRepository;

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

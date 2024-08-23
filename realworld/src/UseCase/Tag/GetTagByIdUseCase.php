<?php

declare(strict_types=1);

namespace App\UseCase\Tag;

use App\Repository\TagRepository;
use App\Entity\Tag;

class GetTagByIdUseCase
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function execute(string $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }
}

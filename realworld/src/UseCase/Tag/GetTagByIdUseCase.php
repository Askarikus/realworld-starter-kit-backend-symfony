<?php

declare(strict_types=1);

namespace App\UseCase\Tag;

use App\Entity\Tag;
use App\Repository\TagRepository;

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

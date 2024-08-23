<?php

declare(strict_types=1);

namespace App\UseCase\Tag;

use App\Entity\Tag;
use App\Repository\TagRepository;

class CreateTagUseCase
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {
    }

    public function execute(string $name): Tag
    {
        $tag = new Tag();
        $tag->setName($name);

        return $this->tagRepository->save($tag);
    }
}

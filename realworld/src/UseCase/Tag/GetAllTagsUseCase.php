<?php

namespace App\UseCase\Tag;

use App\Entity\Tag;
use App\Repository\TagRepository;

class GetAllTagsUseCase
{

    public function __construct(private readonly TagRepository $tagRepository)
    {

    }

    /**
     * Execute the use case to get all tags.
     *
     * @return Tag[]
     */
    public function execute(): array
    {
        $tags = $this->tagRepository->findAll();
        return $tags;
    }
}

<?php

namespace App\Controller\Tag;

use App\Dto\Tag\TagResponseDto;
use App\UseCase\Tag\GetAllTagsUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetTagsController extends AbstractController
{
    public function __construct(private readonly GetAllTagsUseCase $getAllTagsUseCase)
    {

    }

    #[Route(path: 'tags', name: 'tag_get_all', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $tags = $this->getAllTagsUseCase->execute();
        $tagsResponseDto = array_map(fn ($tag) => TagResponseDto::fromModel($tag), $tags);
        return new JsonResponse(
            data: [
                'tags' => $tagsResponseDto,
                'tagsCount' => count($tags)
                ],
            status: Response::HTTP_OK,
        );
    }
}

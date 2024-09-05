<?php

declare(strict_types=1);

namespace App\Controller\File;

use App\Dto\File\FileUploadDto;
use App\Dto\User\UserResponseDto;
use App\Controller\BaseController;
use App\Dto\User\EditUserRequestDto;
use App\Helpers\Request\BaseRequest;
use App\UseCase\User\EditUserUseCase;
use App\UseCase\File\FileUploadUseCase;
use App\UseCase\User\GetAuthUserUseCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UploadFileController extends BaseController
{

    public function __construct(
        private readonly FileUploadUseCase $fileUploadUseCase,
        private readonly EditUserUseCase $editUserUseCase,
        private readonly GetAuthUserUseCase $getAuthUserUseCase,
    ) {

    }
    #[Route(path: '/file-upload', name: 'file_upload', methods: ['POST'])]
    public function __invoke(BaseRequest $request)
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        /** @var UploadedFile $file */
        $file = $request->getRequest()->files->get('image');

        $fileUploadDto = new FileUploadDto($file);

        $file = $this->fileUploadUseCase->execute(
            uploadedFile: $fileUploadDto->getFile()
        );
        // dd(json_decode($request->getRequest()->get('text'), true));
        // $requestData = json_decode($request->getRequest()->get('text'), true)?? [];
        // $requestData['image'] = $file;
        // $editUserRequestDto = EditUserRequestDto::fromArray($requestData);

        // $user = $this->getAuthUserUseCase->execute();

        // try {
        //     $user = $this->editUserUseCase->execute($user->getStringId(), $editUserRequestDto);
        // } catch (NotFoundHttpException $e) {
        //     return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        // }
        // $userResponseDto = UserResponseDto::fromModel($user);

        return new JsonResponse(
            ['file' => $file],
            Response::HTTP_CREATED
        );
    }

}

<?php

declare(strict_types=1);

namespace App\UseCase\File;

use App\Helpers\File\FileUploaderService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadUseCase
{
    public function __construct(
        private readonly FileUploaderService $fileUploaderService)
    {
    }

    public function execute(UploadedFile $uploadedFile): string
    {
        return $this->fileUploaderService->upload($uploadedFile);
    }
}

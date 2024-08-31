<?php

declare(strict_types=1);

namespace App\Helpers\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class FileUploaderService
{
    private const BASE_PATH = '/uploads';

    public function __construct(
        private readonly string $appUrl,
        private readonly SluggerInterface $slugger,
        private readonly Filesystem $filesystem,
        private readonly string $uploadsDirectory
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $result = $file->move($this->uploadsDirectory. "/storage/", $fileName);

            return $this->appUrl . self::BASE_PATH . "/storage/" . $result->getBasename();
        } catch (FileException $e) {
            echo $e->getMessage();
            throw new FileException('Failed to upload file');
        }
    }
}

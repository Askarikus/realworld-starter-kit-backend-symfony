<?php

declare(strict_types=1);

namespace App\Dto\File;

use App\Dto\AbstractRequestDto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class FileUploadDto extends AbstractRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\File]
        private readonly UploadedFile $file,
    ) {
    }

    public static function fromArray(array $data): static
    {
        /** @var UploadedFile $file */
        $file = $data['file'];

        return new static(
            file: $file,
        );
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getFile()->getClientOriginalName(),
        ];
    }
}

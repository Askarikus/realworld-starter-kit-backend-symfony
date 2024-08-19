<?php

declare(strict_types=1);

namespace App\Helpers\Slug;

use App\Repository\AbstractRepository;
use Illuminate\Support\Str;

trait GenerateUniqueSlugTrait
{
    public static function bootGenerateUniqueSlugTrait(): void
    {
        static::saving(function ($model) {
            $slug = $model->slug;
            $model->slug = $model->generateUniqueSlug($slug);
        });
    }

    public function generateUniqueSlug(string $slug): string
    {
        // Check if the slug already has a number at the end
        $originalSlug = $slug;
        $slugNumber = null;

        if (preg_match('/-(\d+)$/', $slug, $matches)) {
            $slugNumber = $matches[1];
            $slug = Str::replaceLast("-$slugNumber", '', $slug);
        }

        // Check if the modified slug already exists in the table
        $existingSlugs = $this->getExistingSlugs($slug, $this->getRepository());

        if (!in_array($slug, $existingSlugs)) {
            // Slug is unique, no need to append numbers
            return $slug . ($slugNumber ? "-$slugNumber" : '');
        }

        // Increment the number until a unique slug is found
        $i = $slugNumber ? ($slugNumber + 1) : 1;
        $uniqueSlugFound = false;

        while (!$uniqueSlugFound) {
            $newSlug = $slug . '-' . $i;

            if (!in_array($newSlug, $existingSlugs)) {
                // Unique slug found
                return $newSlug;
            }

            $i++;
        }

        // Fallback: return the original slug with a random number appended
        return $originalSlug . '-' . mt_rand(1000, 9999);
    }

    private function getExistingSlugs(string $slug, AbstractRepository $repository): array
    {
        return array_map(function ($article){return $article->getSlug();} ,$repository->search('slug', $slug));
    }
}

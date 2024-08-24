<?php

declare(strict_types=1);

namespace App\Validator\Article;

use App\Helpers\Validator\Validator;

class CreateArticleValidator extends Validator
{
    protected array $rule = [
        'title' => 'length:6,500',
        'description' => 'length:6,500',
        'body' => 'length:6,5000',
    ];
    protected array $message  =   [
        'title.length' => 'Title length must be between 6 and 500 characters',
        'description.length' => 'Description length must be between 6 and 500 characters',
        'body.length' => 'Body length must be between 6 and 500 characters',
    ];
}

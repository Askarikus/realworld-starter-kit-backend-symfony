<?php

namespace App\Validator\User;

use App\Helpers\Validator\Validator;

class UserUpdateValidator extends Validator
{
    protected array $rule = [
        'email' => 'email',
        'username' => 'length:6,35',
        'bio' => 'length:6,500',
        'image' => 'length:6,150',
        'password' => 'length:6,35|alphaNum',
    ];

    protected array $message = [
        'email.email' => 'Insert correct email format',
        'username' => 'Username length must be between 6 and 35 characters',
        'bio.length' => 'Bio length must be between 6 and 500 characters',
        'image.length' => 'Image must be between 6 and 150 characters',
        'password.length' => 'Password length must be between 6 and 35 characters',
        'password.alphaNum' => 'Password must contain only alphanumeric characters',
    ];
}

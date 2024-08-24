<?php

namespace App\Validator\User;

use App\Helpers\Validator\Validator;

class UserLoginValidator extends Validator
{
    protected array $rule =   [
        'email' => 'require|email',
        'password' => 'require|length:6,35|alphaNum',
    ];

    protected array $message  =   [
        'email.require' => 'Email is required',
        'password.require' => 'Password is required',
        'password.length' => 'Password length must be between 6 and 35 characters',
        'password.alphaNum' => 'Password must contain only alphanumeric characters',
    ];
}

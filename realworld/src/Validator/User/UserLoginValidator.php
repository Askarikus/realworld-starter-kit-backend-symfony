<?php

namespace App\Validator\User;

use App\Helpers\Validator\Validator;

class UserLoginValidator extends Validator
{
    protected array $rule =   [
        'email' => 'require|email',
        'password' => 'require|length:6,35|alphaNum',
    ];
}

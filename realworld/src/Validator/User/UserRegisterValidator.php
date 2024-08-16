<?php

namespace App\Validator\User;

use App\Helpers\Validator\Validator;

class UserRegisterValidator extends Validator
{
    protected array $rule =   [
        'username' => 'require',
        'email' => 'require|email',
        'password' => 'require|length:6,35',
    ];
}

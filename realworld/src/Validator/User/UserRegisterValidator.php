<?php

namespace App\Validator\User;

use App\Helpers\Validator\Validator;

class UserRegisterValidator extends Validator
{
    protected array $rule =   [
        'username' => 'require',
        'email' => 'require|email',
        'password' => 'require|length:6,35|alphaNum',
    ];

    // public function sceneRegister()
    // {
    //     return $this->only(['username','email','password'])
    //         ->append('name', 'require')
    //         ->append('email', 'require')
    //         ->append('email', 'email')
    //         ->append('password', ['require', 'length:6,35', 'alphaNum']);
    // }
}

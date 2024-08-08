<?php

declare(strict_types=1);

namespace App\Exception\User;

class UserAlreadyExistException extends \Exception
{
    protected string $error_message = 'User with specified email already exists';
}

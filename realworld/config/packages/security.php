<?php

use Symfony\Config\Security\PasswordHasherConfig;
use Symfony\Config\SecurityConfig;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (
    SecurityConfig $security,
    // PasswordHasherConfig $passwordHasherConfig
    ): void {
        // $passwordHasherConfig->id(PasswordAuthenticatedUserInterface);
    $security->firewall('dev')
        ->pattern([
            '^/_profiler/',
            '^/_wdt/',
            '^/css/',
            '^/images/',
            '^/js/',
        ])
        ->security(false);

        $security->firewall('main')
        ->lazy(true);
};

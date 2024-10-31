<?php

declare(strict_types=1);

namespace App\Tests\Fuctional;

use Faker\Factory;
use Faker\Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
abstract class AppTestCase extends WebTestCase
{
    protected KernelBrowser $nonSecureClient;
    protected KernelBrowser $jwtClient;
    protected Generator $faker;
    protected int $timestampInf;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create('ru_RU');
        $this->timestampInf = 1699999999;

        $this->jwtClient = static::createClient();
        $this->jwtClient->disableReboot();

        $this->nonSecureClient = static::createClient();
        $this->nonSecureClient->disableReboot();

        $encoder = $this->jwtClient->getContainer()->get(JWTEncoderInterface::class);
        $payload = $encoder->encode(['username' => 'blank@blank.ru', 'password' => '123']);
        $this->jwtClient->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $payload));
    }
}

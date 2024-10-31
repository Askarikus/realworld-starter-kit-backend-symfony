<?php

declare(strict_types=1);

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixtures extends Fixture implements OrderedFixtureInterface
{
    protected ObjectManager $manager;
    protected Generator $faker;

    protected static array $hundredDays = [];

    protected static function loadHundredDays()
    {
        for ($i = -100; $i < 0; ++$i) {
            self::$hundredDays[] = date('Y-m-d', strtotime("$i days"));
        }
    }

    abstract protected function loadData(ObjectManager $em): void;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create('ru_RU');

        $this->loadData($manager);
    }

    /**
     * @param mixed[] $args
     */
    protected function createMany(
        string $classname,
        int $count,
        array $args,
        callable $factory,
    ): void {
        for ($i = 0; $i < $count; ++$i) {
            $entity = new $classname();
            $factory($entity, $args);

            $this->manager->persist($entity);
        }
    }
}

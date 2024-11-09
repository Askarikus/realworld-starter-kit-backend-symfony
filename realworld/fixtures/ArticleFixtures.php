<?php

declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        // Retrieve a user to associate with the articles
        $userRepository = $manager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'user@user.com']); // Adjust the criteria as needed

        $this->createMany(
            Article::class,
            10, // Number of articles to create
            [],
            function (Article $article, $arr) use ($user) {
                $article->setTitle('Sample Article Title');
                $article->setBody('This is a sample article content.');
                $article->setAuthor($user); // Set the user as the author
                $article->setSlug($this->faker->slug(15)); // Generate a unique slug
                $article->setDescription($this->faker->slug(15)); // Generate a unique slug
                $article->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-200 days')));
                $article->setUpdatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days')));
            }
        );

        $this->manager->flush();
    }

    public function getOrder(): int
    {
        return 2; // Order in which this fixture should be loaded
    }
}

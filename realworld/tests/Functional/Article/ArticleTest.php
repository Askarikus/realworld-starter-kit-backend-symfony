<?php

declare(strict_types=1);

namespace App\Tests\Functional\Article;

use App\Entity\Article;
use App\Tests\Functional\AppTestCase;
use App\Repository\ArticleRepository;

/**
 * @covers \CreateArticleController
 */
class ArticleTest extends AppTestCase
{
    private ArticleRepository $articleRepository;
    private Article $article;

    public function setUp(): void
    {
        parent::setUp();
        $container = $this->getContainer();
        $this->articleRepository = $container->get(ArticleRepository::class);
    }

    public function testCreateArticle(): Article
    {
        $uri = '/articles';
        $content = '{
            "article": {
                "title": "Or how to train your dragon",
                "description": "Ever wonder how? {{$randomLoremWords}}",
                "body": "{{$randomLoremWords}}",
                "tagList": ["dragon"],
                "slug": "Or how to train your dragon"
            }
        }';
        $content = str_replace('{{$randomLoremWords}}', $this->faker->realText(200), $content);

        $this->jwtClient->request(
            method: 'POST',
            uri: $uri,
            parameters: [],
            files: [],
            server: [],
            content: $content
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $responseContent = $this->jwtClient->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        // Check that the response contains the expected structure
        $this->assertArrayHasKey('article', $responseData, 'Response should contain an article key');
        $articleData = $responseData['article'];
        $this->assertArrayHasKey('slug', $articleData, 'Article should have a slug');
        $this->assertArrayHasKey('title', $articleData, 'Article should have a title');
        $this->assertArrayHasKey('description', $articleData, 'Article should have a description');
        $this->assertArrayHasKey('body', $articleData, 'Article should have a body');
        $this->assertArrayHasKey('author', $articleData, 'Article should have an author');
        $this->assertArrayHasKey('tagList', $articleData, 'Article should have a tagList');
        $this->assertArrayHasKey('favoritedByCurrentUser', $articleData, 'Article should have favoritedByCurrentUser');
        $this->assertArrayHasKey('favoritesCount', $articleData, 'Article should have favoritesCount');
        $this->assertArrayHasKey('createdAt', $articleData, 'Article should have createdAt');

        // Fetch the article entity from the repository using the slug
        $this->article = $this->articleRepository->findOneBy(['slug' => $articleData['slug']]);
        $this->assertNotNull($this->article, 'Article should be found in the database');

        return $this->article;
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Fuctional\Article;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Tests\Fuctional\AppTestCase;

/**
 * @covers \CreateArticleController
 */
class ArticleTest extends AppTestCase
{
    private ArticleRepository $articleRepository;

    public function setUp(): void
    {
        parent::setUp();
        $container = $this->getContainer();
        $this->articleRepository = $container->get(ArticleRepository::class);
    }

    public function testCreateArticle(): Article
    {
    }
}

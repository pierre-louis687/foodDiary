<?php
namespace App\Tests\Entity;
use App\Entity\Article;
use PHPUnit\Framework\TestCase;
class ArticleTest extends TestCase
{
    public function testUri()
    {
        $article = new Article();
        $uri = "Test 2";
        
        $article->setUri($uri);
        $this->assertEquals("test_2", $article->getUri());
    }
}
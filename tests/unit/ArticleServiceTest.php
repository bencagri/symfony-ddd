<?php
namespace Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ArticleServiceTest extends WebTestCase
{

    public function setUp()
    {
        self::bootKernel();
    }
    public function test_add_article_from_service()
    {
        $articleService = self::$kernel->getContainer()->get('aurora.article.service');

        //create a request mock
        $request = new Request([],[
            'user' => 1,
            'title' => 'Test Title',
            'body' => 'this is html body'
        ]);

        $articleService->addArticle($request);

    }
}

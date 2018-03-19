<?php


namespace App\Aurora\Http\Controller;

use App\Aurora\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AppController
{

    /**
     * @var ArticleService
     */
    private $articleService;


    public function setArticleService(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index()
    {
        $articles = $this->articleService->getArticles();

        return $this->response($articles);

    }

    public function create(Request $request)
    {

        $success = $this->articleService->addArticle($request);

    }

}
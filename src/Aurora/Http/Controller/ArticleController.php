<?php


namespace App\Aurora\Http\Controller;

use App\Aurora\App\Support\AppController;
use App\Aurora\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AppController
{

    /**
     * @var ArticleService
     */
    private $articleService;

    public function __construct(ArticleService $articleService)
    {

        $this->articleService = $articleService;
    }

    public function index()
    {
        return new JsonResponse($this->articleService->getArticles());
    }

    public function create(Request $request)
    {

    }

}
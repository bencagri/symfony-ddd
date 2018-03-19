<?php


namespace App\Aurora\Http\Controller;

use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AppController
{


    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * @var FractalService
     */
    private $fractalService;

    public function __construct(ArticleService $articleService, FractalService $fractalService)
    {
        $this->articleService = $articleService;
        $this->fractalService = $fractalService;
    }


    public function index()
    {
        $articles = $this->articleService->getArticles();

        return new JsonResponse($this->fractalService->transform($articles));
    }

    public function create(Request $request)
    {
        try {
            $this->articleService->addArticle($request);
        }catch (\Exception $exception) {

        }

    }

}
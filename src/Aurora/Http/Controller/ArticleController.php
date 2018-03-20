<?php


namespace App\Aurora\Http\Controller;

use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ArticleService $articleService, FractalService $fractalService, RouterInterface $router)
    {
        $this->articleService = $articleService;
        $this->fractalService = $fractalService;
        $this->router = $router;
    }


    public function index(Request $request)
    {
        $articles = $this->articleService->getArticles($request, $this->router);

        return new JsonResponse($this->fractalService->transform($articles));
    }

    public function create(Request $request)
    {
        try {
            $this->articleService->addArticle($request);
            return new JsonResponse($this->fractalService->transform('Article has been added'),Response::HTTP_OK);
        }catch (\Exception $exception) {
            return new JsonResponse($this->fractalService->transform('Something went wrong', false),Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
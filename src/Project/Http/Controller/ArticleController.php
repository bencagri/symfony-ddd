<?php


namespace App\Project\Http\Controller;

use App\Project\App\Support\FractalService;
use App\Project\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Swagger\Annotations as SWG;

class ArticleController
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

    public function __construct(
        ArticleService $articleService,
        FractalService $fractalService,
        RouterInterface $router
    )
    {
        $this->articleService = $articleService;
        $this->fractalService = $fractalService;
        $this->router = $router;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="Returns the article collection"
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="current page",
     *     default="1"
     * )
     * @SWG\Parameter(
     *     name="per_page",
     *     in="query",
     *     type="integer",
     *     description="limit per page",
     *     default="10"
     * )
     * @SWG\Tag(name="articles")
     */
    public function index(Request $request)
    {
        $articles = $this->articleService->getArticles($request, $this->router);

        return new JsonResponse($this->fractalService->transform($articles));
    }

    /**
     * @param $id
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="Returns single article Item"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Returns error"
     * )
     * @SWG\Tag(name="articles")
     */
    public function article($id)
    {
        try {
            $article = $this->articleService->getArticleById($id);
            return new JsonResponse($this->fractalService->transform($article));
        }catch (\Exception $e){
            return new JsonResponse($this->fractalService->transform($e->getMessage(),false), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="returns success message"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Returns error"
     * )
     *
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="article title"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="article body, markdown accepted"
     * )
     * @SWG\Parameter(
     *     name="user",
     *     in="formData",
     *     type="integer",
     *     required=true,
     *     description="user id from GET /users"
     * )
     * @SWG\Parameter(
     *     name="tags",
     *     in="formData",
     *     type="array",
     *     required=false,
     *     @SWG\Items(
     *         type="string"
     *     ),
     *     description="post tags"
     * )
     * @SWG\Tag(name="articles")
     */
    public function create(Request $request)
    {
        try {
            $this->articleService->addArticle($request);
            return new JsonResponse($this->fractalService->transform('Article has been added'),Response::HTTP_OK);
        }catch (\Exception $exception) {
            return new JsonResponse($this->fractalService->transform($exception->getMessage(), false),Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
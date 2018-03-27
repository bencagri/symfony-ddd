<?php

namespace App\Aurora\Http\Controller;


use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\Article\ArticleService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class SearchController
{


    /**
     * @var ArticleService
     */
    private $articleService;


    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="Returns the article collection"
     * )
     * @SWG\Parameter(
     *     name="query",
     *     in="query",
     *     type="integer",
     *     description="search query",
     *     default="1"
     * )
     * @SWG\Tag(name="search")
     */
    public function index(Request $request)
    {
        $resource = $this->articleService->searchArticle($request);

        return new JsonResponse($resource);

    }
}
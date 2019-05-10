<?php

namespace App\Project\Http\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;


class HomeController extends AppController
{

    /**
     * @param Request $request
     * @return Response
     * @SWG\Response(
     *     response=200,
     *     description="Returns the rewards of an user"
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     type="integer",
     *     description="The field used to order rewards"
     * )
     * @SWG\Tag(name="home")
     */
    public function index(Request $request)
    {
        return new JsonResponse(['order' => $request->get('order')]);
    }
}
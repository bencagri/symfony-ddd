<?php

namespace App\Project\Http\Controller;

use App\Project\App\Support\FractalService;
use App\Project\Domain\User\UserService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Swagger\Annotations as SWG;


class UserController
{

    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FractalService
     */
    private $fractalService;

    public function __construct(
        UserService $userService,
        RouterInterface $router,
        FractalService $fractalService
    )
    {

        $this->userService = $userService;
        $this->router = $router;
        $this->fractalService = $fractalService;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="Returns users collection with pagination"
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Returns error"
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
     * @SWG\Tag(name="users")
     */
    public function index(Request $request)
    {
        $resource = $this->userService->listUsers($request, $this->router);

        return new JsonResponse($this->fractalService->transform($resource));
    }

    /**
     * @param $id
     * @return JsonResponse
     * @SWG\Response(
     *     response=200,
     *     description="Returns single user Item"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Returns not found user"
     * )
     * @SWG\Tag(name="users")
     */
    public function user($id)
    {

        try {
            $user = $this->userService->getUserById($id);
            return new JsonResponse($this->fractalService->transform($user));

        }catch (EntityNotFoundException $exception) {
            return new JsonResponse($this->fractalService->transform($exception->getMessage(), false), Response::HTTP_NO_CONTENT);
        }
    }
}
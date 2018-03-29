<?php


namespace App\Aurora\Domain\User;


use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\User\Entity\User;
use App\Aurora\Infrastructure\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UserService
{


    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FractalService
     */
    private $fractalService;
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    public function __construct(
        EntityManagerInterface $entityManager,
        FractalService $fractalService,
        UserTransformer $userTransformer
    )
    {

        $this->entityManager = $entityManager;
        $this->fractalService = $fractalService;
        $this->userTransformer = $userTransformer;
    }

    public function listUsers(Request $request, RouterInterface $router)
    {
        $page = NULL !== $request->get('page') ? (int) $request->get('page') : 1;
        $perPage = NULL !== $request->get('per_page') ? (int) $request->get('per_page') : 10;

        $users = $this->entityManager->getRepository(User::class);

        $doctrineAdapter = new DoctrineORMAdapter($users->getUsers());
        $paginator = new Pagerfanta($doctrineAdapter);
        $paginator->setCurrentPage($page);
        $paginator->setMaxPerPage($perPage);

        $filteredResults = $paginator->getCurrentPageResults();

        $paginatorAdapter = new PagerfantaPaginatorAdapter($paginator, function(int $page) use ($request, $router) {
            $route = $request->attributes->get('_route');
            $inputParams = $request->attributes->get('_route_params');
            $newParams = array_merge($inputParams, $request->query->all());
            $newParams['page'] = $page;
            return $router->generate($route, $newParams, 0);
        });

        $resource = new Collection($filteredResults,$this->userTransformer, 'user');
        $resource->setPaginator($paginatorAdapter);
        return $resource;
    }

    public function getUserById($id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user)
            throw new EntityNotFoundException('user not found');

        return new Item($user,$this->userTransformer,'user');

    }
}
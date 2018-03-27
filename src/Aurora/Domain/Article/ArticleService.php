<?php

namespace App\Aurora\Domain\Article;

use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\Article\Entity\Article;
use App\Aurora\Domain\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ArticleService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ArticleTransformer
     */
    private $articleTransformer;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FractalService
     */
    private $fractalService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleTransformer $articleTransformer,
        FractalService $fractalService
    )
    {

        $this->entityManager = $entityManager;
        $this->articleTransformer = $articleTransformer;
        $this->fractalService = $fractalService;
    }

    /**
     * @param Request $request
     * @param RouterInterface $router
     * @return Collection|Item
     */
    public function getArticles(Request $request, RouterInterface $router)
    {
        $page = NULL !== $request->get('page') ? (int) $request->get('page') : 1;
        $perPage = NULL !== $request->get('per_page') ? (int) $request->get('per_page') : 10;

        $articles = $this->entityManager->getRepository(Article::class);

        $doctrineAdapter = new DoctrineORMAdapter($articles->getArticles());
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

        $resource = new Collection($filteredResults,$this->articleTransformer, 'article');
        $resource->setPaginator($paginatorAdapter);
        return $resource;
    }

    /**
     * @param $id
     * @return Item
     * @throws EntityNotFoundException
     */
    public function getArticleById($id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        if ($article)
            return new Item($article, $this->articleTransformer, 'article');

        throw new EntityNotFoundException("Article not found");
    }

    /**
     * @param Request $request
     */
    public function addArticle(Request $request)
    {

        $userRepository = $this->entityManager->getRepository(User::class);

        $user = null;
        if ($request->request->get('user')) {
            $user = $userRepository->find($request->request->get('user'));
        }

        if (!$user){
            $user = new User();
            $user->setUsername(str_shuffle('acbaksdlfurieowpvmznhqqurlfotpcnvbhrtsa'));
        }

        $article = new Article();
        $article->setTitle($request->request->get('title'));
        $article->setBody($request->request->get('body'));
        $article->setAuthor($user);
        $article->setContributors(new ArrayCollection([$user]));

        $this->entityManager->getRepository(Article::class)->save($article);

    }


    public function searchArticle(Request $request)
    {
        $resource = $this->entityManager->getRepository(Article::class)->searchArticle($request);

        $collection = new Collection($resource,$this->articleTransformer,'article');

        return $this->fractalService->transform($collection);
    }
}
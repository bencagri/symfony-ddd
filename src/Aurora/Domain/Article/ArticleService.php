<?php

namespace App\Aurora\Domain\Article;

use App\Aurora\Domain\Article\Entity\Article;
use App\Aurora\Domain\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Pagination\Cursor;
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

    public function __construct(EntityManagerInterface $entityManager, ArticleTransformer $articleTransformer)
    {

        $this->entityManager = $entityManager;
        $this->articleTransformer = $articleTransformer;
    }

    /**
     * @param Request $request
     * @param RouterInterface $router
     * @return Collection|Item
     */
    public function getArticles(Request $request, RouterInterface $router)
    {
        $page = NULL !== $request->get('page') ? (int) $request->get('page') : 1;
        $articles = $this->entityManager->getRepository(Article::class);

        $doctrineAdapter = new DoctrineORMAdapter($articles->getArticles());
        $paginator = new Pagerfanta($doctrineAdapter);
        $paginator->setCurrentPage($page);
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

        $this->entityManager->persist($article);
        $this->entityManager->flush();

    }
}
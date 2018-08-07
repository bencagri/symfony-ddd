<?php

namespace App\Aurora\Domain\Article;

use App\Aurora\App\Support\FractalService;
use App\Aurora\Domain\Article\Entity\Article;
use App\Aurora\Domain\User\Entity\User;
use App\Aurora\Infrastructure\Article\ArticleRepository;
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
     * @var ArticleRepository
     */
    private $articleRepository;

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
        $this->articleRepository = $this->entityManager->getRepository(Article::class);
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

        $doctrineAdapter = new DoctrineORMAdapter($this->articleRepository->getArticles());
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

        $resource = new Collection($filteredResults, $this->articleTransformer, 'article');
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
        $article = $this->articleRepository->find($id);

        if ($article) {
            return new Item($article, $this->articleTransformer, 'article');
        }

        throw new EntityNotFoundException("Article not found");
    }

    /**
     * @param Request $request
     */
    public function addArticle(Request $request)
    {
        var_dump($request);

        /** @var User $user */
        $user =  $this->entityManager->getReference(User::class,$request->request->get('user'));

        $article = new Article();
        $article->setTitle($request->request->get('title'));
        $article->setBody($request->request->get('body'));
        $article->setAuthor($user);
        $article->setContributors(new ArrayCollection([$user]));

        // set tags
        if (is_array($request->request->get('tags'))) {
            foreach ($request->request->get('tags') as $tag) {
                $article->addTagFromName($tag);
            }
        }

        $this->articleRepository->save($article);

    }


    public function searchArticle(Request $request)
    {
        $resource = $this->articleRepository->searchArticle($request);

        $collection = new Collection($resource,$this->articleTransformer,'article');

        return $this->fractalService->transform($collection);
    }
}
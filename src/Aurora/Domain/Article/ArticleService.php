<?php

namespace App\Aurora\Domain\Article;

use App\Aurora\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\Request;

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

    public function __construct(EntityManagerInterface $entityManager, ArticleTransformer $articleTransformer)
    {

        $this->entityManager = $entityManager;
        $this->articleTransformer = $articleTransformer;
    }

    /**
     * @return Collection|Item
     */
    public function getArticles()
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        return new Collection($articles,$this->articleTransformer);
    }

    public function addArticle(Request $request)
    {

        $userRepository = $this->entityManager->getRepository(User::class);

        $user = $userRepository->find($request->request->get('user'));

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
<?php

namespace App\Aurora\Domain\Article;

use App\Aurora\Infrastructure\Article\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function getArticles()
    {
        return $this->entityManager->getRepository(Article::class)->findAll();
    }

    public function addArticle(Request $request)
    {
        // add article
    }
}
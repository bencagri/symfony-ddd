<?php


namespace App\Project\Infrastructure\Article;

use App\Project\App\Support\AppEntityRepository;
use App\Project\Domain\Article\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

class ArticleRepository extends AppEntityRepository
{

    public function getArticles()
    {
        $qb = $this->createQueryBuilder('a');

        return $qb->getQuery();
    }

    public function searchArticle(Request $request)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.title LIKE :query')
            ->orWhere('a.body LIKE :query')
            ->setParameter('query', "%{$request->get('query')}%");

        return $qb->getQuery()->getResult();
    }



    public function save(Article $article)
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }
}
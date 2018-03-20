<?php


namespace App\Aurora\Infrastructure\Article;

use App\Aurora\App\Support\AppEntityRepository;
use App\Aurora\Domain\Article\Entity\Article;

class ArticleRepository extends AppEntityRepository
{

    public function getArticles()
    {
        return $this->createQueryBuilder('a')
            ->getQuery();
    }
}
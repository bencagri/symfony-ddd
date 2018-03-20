<?php


namespace App\Aurora\Domain\Article;


use App\Aurora\Domain\Article\Entity\Article;
use League\Fractal\Manager;

class ArticleTransformer extends Manager
{
    /**
     * @param Article $article
     * @return array
     */
    public function transfom(Article $article)
    {
        return [
            'title' => $article->getTitle(),
            'body' => $article->getBody(),
            'tags' => $article->getTags(),
            'createdAt' => date('Y-m-d')
        ];
    }

}
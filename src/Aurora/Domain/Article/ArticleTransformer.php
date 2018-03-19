<?php


namespace App\Aurora\Domain\Article;


use App\Aurora\Domain\Article\Entity\Article;
use League\Fractal\Manager;

class ArticleTransformer extends Manager
{
    public function transfom(Article $article)
    {
        return [
            'title' => $article->getTitle(),
            'body' => $article->getBody(),
            'createdAt' => date('Y-m-d')
        ];
    }

}
<?php


namespace App\Project\Domain\Article;


use App\Project\Domain\Article\Entity\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract
{
    /**
     * @param Article $article
     * @return array
     */
    public function transform(Article $article)
    {
        return [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'body' => $article->getBody(),
            'tags' => $article->getTags(),
            'createdAt' => date('Y-m-d')
        ];
    }

}
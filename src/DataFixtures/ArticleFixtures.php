<?php

namespace App\DataFixtures;

use App\Aurora\Domain\Article\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <= 50; $i++){
            $article = new Article();

            $article->setContributors(
                new ArrayCollection([$this->getReference(UserFixtures::TEST_USER)])
            );

            $article->setAuthor($this->getReference(UserFixtures::TEST_USER));
            $article->setBody(str_repeat('lorem ipsum dolor sit amet.',10));
            $article->setTitle('May Test Article '. $i);

            $manager->persist($article);
            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}

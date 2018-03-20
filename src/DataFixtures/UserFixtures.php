<?php

namespace App\DataFixtures;

use App\Aurora\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Aurora');

        $manager->persist($user);
        $manager->flush();
    }
}

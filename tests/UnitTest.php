<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UnitTest extends WebTestCase
{
    /** @var  ContainerInterface $container */
    protected $container;

    /** @var  EntityManagerInterface $em */
    protected $em;

    protected function setUp()
    {
        self::bootKernel(['environment' => "test"]);
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->loadFixtures();
    }

    public function loadFixtures()
    {
        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__ . '/../src/DataFixtures');
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em,$purger);
        $executor->execute($loader->getFixtures());
    }

    public function test_sample()
    {
        $this->assertTrue(true);
    }
}

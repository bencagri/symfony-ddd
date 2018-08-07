<?php

namespace App\DataFixtures;

use App\Aurora\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    const TEST_USER = 'test-user';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $salt = $this->generateSalt();

        $user = new User();
        $user->setUsername($faker->userName);
        $user->setEmail("user@example.com");
        $user->setRoles(['ROLE_USER']);

        $plainPassword = 'secret';

        // See https://symfony.com/doc/current/book/security.html#security-encoding-password
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::TEST_USER, $user);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

    private function generateSalt(): string
    {
        $bytes = random_bytes(32);

        return base_convert(bin2hex($bytes), 16, 36);
    }
}

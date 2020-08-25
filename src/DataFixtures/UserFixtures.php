<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setFirstname("Dennis");
        $user->setLastname("de Best");
        $user->setPassword('password');
        $user->setEmail('dennis@codebuds.com');
        $user->setAdminEmailReceiver(true);
        $user->setRole('ROLE_SUPER_ADMIN');

        $manager->persist($user);

        $this->addReference('user_dennis', $user);

        $manager->flush();
    }
}

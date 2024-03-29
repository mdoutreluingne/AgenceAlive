<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'adminadmin'));
        $user->setEmail("maxime.doutreluingne@sfr.fr");
        $user->setIsVerified(true);
        $user->setLocale('fr');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('test');
        $user->setRoles(array('ROLE_USER'));
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'testtest'));
        $user->setEmail("test.test@sfr.fr");
        $user->setIsVerified(true);
        $user->setLocale('fr');
        $manager->persist($user);

        $manager->flush();
    }
}

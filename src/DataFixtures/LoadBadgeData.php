<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadBadgeData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $badge = new Badge();
        $badge->setName('timide');
        $badge->setActionCount(1);
        $badge->setActionName('property');

        $manager->persist($badge);
        $manager->flush();
    }
}

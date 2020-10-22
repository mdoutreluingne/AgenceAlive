<?php

namespace App\EventSubscriber;

use App\Mailer\Mailer;
use App\Entity\Property;
use App\Manager\BadgeManager;
use App\Event\BadgeUnlockedEvent;
use App\Event\PropertyCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeSubscriber implements EventSubscriberInterface
{
    /**
     *
     * @var Mailer
     */
    private $mailer;

    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     * @var BadgeManager
     */
    private $badge_manager;

    public function __construct(Mailer $mailer, EntityManagerInterface $em, BadgeManager $badge_manager)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->badge_manager = $badge_manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            BadgeUnlockedEvent::NAME => 'onBadgeUnlock',
            PropertyCreatedEvent::NAME => 'onNewProperty'
        ];
    }

    public function onBadgeUnlock(BadgeUnlockedEvent $event)
    {
        $this->mailer->badgeUnlocked($event->getBadge(), $event->getUser());
    }

    public function onNewProperty(PropertyCreatedEvent $event)
    {
        $user = $event->getProperty()->getUsers();
        $property_count = $this->em->getRepository(Property::class)->countForUser($user->getId());
        $this->badge_manager->checkAndUnlock($user, 'property', $property_count);
    }

}

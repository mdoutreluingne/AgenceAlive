<?php 

namespace App\Event;

use App\Entity\Badge;
use App\Entity\BadgeUnlock;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class BadgeUnlockedEvent extends Event
{
    const NAME = 'badge.unlock';

    /**
     *
     * @var BadgeUnlock
     */
    private $badgeUnlock;

    public function __construct(BadgeUnlock $badgeUnlock)
    {
        $this->badgeUnlock = $badgeUnlock;
    }

    /**
     * Get the value of badgeUnlock
     *
     * @return  BadgeUnlock
     */ 
    public function getBadgeUnlock()
    {
        return $this->badgeUnlock;
    }

    public function getBadge(): Badge
    {
        return $this->badgeUnlock->getBadge();
    }

    public function getUser(): User
    {
        return $this->badgeUnlock->getUser();
    }
}

?>
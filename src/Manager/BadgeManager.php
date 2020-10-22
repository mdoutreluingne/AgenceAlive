<?php 

namespace App\Manager;

use App\Entity\Badge;
use App\Entity\User;
use App\Entity\BadgeUnlock;
use App\Event\BadgeUnlockedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BadgeManager
{

    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     *
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Check if a badge exists for this action and action occurence and unlock it for the current user
     *
     * @param User $user
     * @param string $action
     * @param integer $action_count
     * @return void
     */
    public function checkAndUnlock(User $user, string $action, int $action_count): void
    {
        //Vérifier si on a un badge qui correspond à action et a action count
        try {
            $badge = $this->em->getRepository(Badge::class)->findWithUnlockForAction($user->getId(), $action, $action_count);

            //Vérifier si l'utilisateur à déjà ce badge
            if ($badge->getUnlocks()->isEmpty()) {
                //Débloquer le badge pour l'utilisateur en question
                $unlock = new BadgeUnlock();
                $unlock->setBadge($badge);
                $unlock->setUser($user);
                $this->em->persist($unlock);
                $this->em->flush();

                //Emmetre un événement pour informer l'application du déblocage du badge
                $this->dispatcher->dispatch(new BadgeUnlockedEvent($unlock), BadgeUnlockedEvent::NAME);
            }

        } catch (NoResultException $e) {
            throw $e;
        }       
        
    }

    /**
     * Get badges unlocked for the current user
     *
     * @param User $user
     * @return array
     */
    public function getBadgeFor(User $user): array
    {
        return $this->em->getRepository(Badge::class)->findUnlockFor($user->getId());
    }
}

?>
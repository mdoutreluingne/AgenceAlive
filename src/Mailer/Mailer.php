<?php 

namespace App\Mailer;

use App\Entity\User;
use App\Entity\Badge;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function badgeUnlocked(Badge $badge, User $user)
    {
        $email = (new TemplatedEmail())
            ->from('noreplay@gmail.com')
            ->to($user->getEmail())
            ->subject('Vous avez obtenu un nouvezu badge !')
            ->htmlTemplate('emails/badge.html.twig')
            ->context([
                'badge' => $badge,
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}


?>
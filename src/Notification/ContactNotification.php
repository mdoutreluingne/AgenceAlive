<?php 
namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactNotification {

    /**
     *
     * @var MailerInterface
     */
    private $mailer;

    /**
     *
     * @var Environment
     */
    private $renderer;

    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {
        $email = (new Email())
            ->from('noreply@agence.fr')
            ->to('contact@agence.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($contact->getEmail())
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Agence : ' . $contact->getProperty()->getTitle())
            ->html($this->renderer->render('emails/contact.html.twig', [
                'contact' => $contact
            ]), 'text/html');

        $this->mailer->send($email);
    }

    
}
?>
<?php


namespace App\EventListener;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriber
{

    private UserPasswordEncoderInterface $encoder;

    private MailerInterface $mailer;

    private SessionInterface $session;

    public function __construct(UserPasswordEncoderInterface $encoder, MailerInterface $mailer, SessionInterface $session)
    {
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var User $entity */
        $entity = $args->getObject();
        if ($entity instanceof User && $entity->getCreatedAt() === null ){
            $password = $entity->getPassword() ?? $this->randomPassword();
            $encodedPassword = $this->encoder->encodePassword($entity, $password);
            $entity->setPassword($encodedPassword);

            $email = (new TemplatedEmail())
                ->to($entity->getEmail())
                ->subject('Your account has been created')
                ->htmlTemplate('email/new_account.inky.twig')
                ->context([
                    'password' => $password,
                ])
            ;
            try {
                $this->mailer->send($email);
            } catch (TransportExceptionInterface $exception){
                $this->session->getFlashBag()->add('error', $exception->getMessage());
                return false;
            }
        }
    }

    public function getSubscribedEvents()
    {
       return [
           Events::prePersist
       ];
    }

    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}
<?php

namespace App\MessageHandler\Event;

use App\Message\Event\SaveRegisteredUserEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\ImportantAction;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Mime\Email;

class UserRegristeredSendMail implements MessageHandlerInterface
{
    private $mailer;
    
    private $userRepository;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function __invoke(SaveRegisteredUserEvent $saveRegisteredUserEvent)
    {
        $email = $saveRegisteredUserEvent->getEmail();

        $user = $this->userRepository->findOneBy(['email' => $email]);

        $this->mailer->send(
            (new Email())
                ->to($email)
                ->subject('Important action made')
                ->html('<h1>Important action</h1><p>Made by ' . $user->getFirstName().' '.$user->getLastName() . '</p>')
        );
    }
}

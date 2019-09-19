<?php

namespace App\MessageHandler;

use App\Message\AddEmailFromRegrister;
use App\Message\Event\SaveRegisteredUserEvent;
use App\Message\SaveRegisteredUser;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SaveRegisteredUserHandler implements MessageHandlerInterface
{
    private $em;

    private $eventBus;

    private $userRepository;

    public function __construct(MessageBusInterface $eventBus, EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->eventBus = $eventBus;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function __invoke(SaveRegisteredUser $saveRegisteredUser)
    {
        $user = $saveRegisteredUser->getUser();
        $email = $user->getEmail();
       
        // TODO dispatch event UserWasRegistered how do:
        // todo first => Send the user an Email
        // todo secend => Add the user to a CRM system in the UserWasRegristered dispatch event 
        // todo EXAMPLE => $this->messageBus->dispatch(new UserAddToCRM($userId))
        $this->em->persist($user);
        $this->em->flush();

        $this->eventBus->dispatch(new SaveRegisteredUserEvent($email));
    }
}

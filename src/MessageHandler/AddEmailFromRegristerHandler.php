<?php

namespace App\MessageHandler;

use App\Message\AddEmailFromRegrister;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddEmailFromRegristerHandler implements MessageHandlerInterface
{
    public function __invoke(AddEmailFromRegrister $AddEmailFromRegrister)
    {
        $user = $AddEmailFromRegrister->getUser();
        dd($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}

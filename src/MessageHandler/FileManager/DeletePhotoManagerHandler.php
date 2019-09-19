<?php

namespace App\MessageHandler\FileManager;

use App\FileManager\PhotoFileManager;
use App\Message\FileManager\DeletePhotoManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeletePhotoManagerHandler implements MessageHandlerInterface
{
    private $messageBus;
    
    private $photoManager;

    private $em;

    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $em, PhotoFileManager $photoManager)
    {
        $this->photoManager = $photoManager;
        $this->messageBus = $messageBus;
        $this->em = $em;
    }

    public function __invoke(DeletePhotoManager $deletePhotoManager)
    {
        $file_manager = $deletePhotoManager->getFileManager();
        $filename = $file_manager->getFilename();

        $this->em->remove($file_manager);
        $this->em->flush();

        $this->photoManager->deletePhoto($filename);
    }
}

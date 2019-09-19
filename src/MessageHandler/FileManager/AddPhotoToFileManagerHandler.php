<?php

namespace App\MessageHandler\FileManager;

use App\Entity\FileManager;
use App\Message\FileManager\AddPhotoToFileManager;
use App\FileManager\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddPhotoToFileManagerHandler implements MessageHandlerInterface
{
    private $photo_manager;
    private $em;

    public function __construct(PhotoFileManager $photo_manager, EntityManagerInterface $em)
    {
        $this->photo_manager = $photo_manager;
        $this->em = $em;
    }

    public function __invoke(AddPhotoToFileManager $addPhotoToFileManager)
    {
        $file_manager = $addPhotoToFileManager->getFileManager();

        $file_manager->setFilename($this->photo_manager->uploadImage($addPhotoToFileManager->getFile()));
        $file_manager->setOriginalFilename($addPhotoToFileManager->getOriginalFilename());

        $this->em->persist($file_manager);
        $this->em->flush();
    }
}

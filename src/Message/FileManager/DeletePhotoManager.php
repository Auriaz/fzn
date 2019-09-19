<?php

namespace App\Message\FileManager;

use App\Entity\FileManager;


class DeletePhotoManager
{
    private $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function getFileManager()
    {
        return $this->fileManager;
    }
}

<?php

namespace App\Message\FileManager;

use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPhotoToFileManager
{
    private $file;

    private $fileManager;

    public function __construct(File $file, FileManager $fileManager)
    {
        $this->file = $file;
        $this->fileManager = $fileManager;
    }

    public function getFileManager()
    {
        return $this->fileManager;
    }

    public function getFile()
    {
        return $this->file;
    }


    public function getOriginalFilename()
    {
        return $this->file->getClientOriginalName();
    }

    public function getMimeType()
    {
        return $this->file->getMimeType() ?? 'application/octet-stream';
    }
}

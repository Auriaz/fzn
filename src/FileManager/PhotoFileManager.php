<?php
namespace App\FileManager;

use App\Entity\FileManager;
use App\Service\UploaderHelper;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoFileManager
{
    const IMAGE = 'images';

    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function uploadImage(File $file)
    {
        return $this->uploaderHelper->uploadFile($file, self::IMAGE, true);
    }

    public function getPublicPathPhoto(FileManager $file): string
    {
        return $this->uploaderHelper->getPublicPath(self::IMAGE.'/'.$file->getFilename());
    }

    public function deletePhoto(string $filename): void
    {
        // make it a bit slow
        sleep(1);

        $this->uploaderHelper->deleteFile(self::IMAGE . '/' . $filename, true);
    }
}
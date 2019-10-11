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

    const ARTICLE_IMAGE = 'article_image';

    const ARTICLE_REFERENCE = 'article_reference';

    const ANIMAL_IMAGE = 'animal_image';

    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function uploadArticleImage(File $file, ?string $existingFilename): string
    {
        $newFilename = $this->uploadImage($file, self::ARTICLE_IMAGE, true);

        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::ARTICLE_IMAGE . '/' . $existingFilename);

                if ($result === false) {
                    throw new \Exception(sprintf('Could not delete old uploaded file "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return $newFilename;
    }

    public function uploadAnimalImage(File $file, ?string $existingFilename): string
    {
        $newFilename = $this->uploadImage($file, self::ANIMAL_IMAGE, true);

        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::ANIMAL_IMAGE . '/' . $existingFilename);

                if ($result === false) {
                    throw new \Exception(sprintf('Could not delete old uploaded file "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return $newFilename;
    }

    public function uploadArticleReference(File $file): string
    {
        return $this->uploadImage($file, self::ARTICLE_REFERENCE, false);
    }

    public function uploadImage(File $file, string $directory = null, bool $isPublic = true)
    {
        if($directory) {
            $directory = self::IMAGE.'/'.$directory;
        } else {
            $directory = self::IMAGE;
        }

        return $this->uploaderHelper->uploadFile($file, $directory, $isPublic);
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
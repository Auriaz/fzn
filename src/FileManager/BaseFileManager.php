<?php

namespace App\FileManager;

use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;

abstract class BaseFileManager
{
    // const ARTICLE_IMAGE = 'article_image';
    // const IMAGE = 'images';
    // const ARTICLE_REFERENCE = 'article_reference';

    protected $filesystem;

    protected $privateFilesystem;

    protected $requestStackContext;

    protected $logger;

    protected $publicAssetBaseUrl;

    public function __construct(FilesystemInterface $publicUploadsFilesystem, FilesystemInterface $privateUploadsFilesystem, RequestStackContext $requestStackContext, LoggerInterface $logger, string $uploadedAssetsBaseUrl)
    {
        $this->filesystem = $publicUploadsFilesystem;
        $this->privateFilesystem = $privateUploadsFilesystem;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
    }
}

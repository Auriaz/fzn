<?php

namespace App\FileManager;

use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;

abstract class BaseFileManager
{

    public function __construct()
    {

    }

    public function getMimeType()
    {

    }
}

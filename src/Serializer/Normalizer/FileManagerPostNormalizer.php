<?php

namespace App\Serializer\Normalizer;

use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;
use Psr\Container\ContainerInterface;


class FileManagerPostNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;
    private $photo_manager;
    private $router;
    private $containerInterface;

    public function __construct(ObjectNormalizer $normalizer, PhotoFileManager $photo_manager, UrlGeneratorInterface $router, ContainerInterface $containerInterface)
    {
        $this->normalizer = $normalizer;
        $this->photo_manager = $photo_manager;
        $this->router = $router;
        $this->containerInterface = $containerInterface;
    }

    public function normalize($fileManager, $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($fileManager, $format, $context);

        // a custom, and therefore "poor" way of adding a link to myself
        // formats like JSON-LD (from API Platform) do this in a much
        // nicer and more standardized way
        $data['@id'] = $this->router->generate('get_file_post_item', [
            'id' => $fileManager->getId()
        ]);

        $data['url'] = $this->photo_manager->getPublicPathPhoto($fileManager);
        $data['urlSmall'] = $this->photo_manager->getPublicPathPhoto($fileManager);
        $data['location'] = '/'.$this->containerInterface->getParameter('uploads_dir_name').'/'. PhotoFileManager::IMAGE.'/'. $fileManager->getFilename();

     
        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FileManager;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}

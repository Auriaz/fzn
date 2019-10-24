<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $markdownLogger;
    private $isDebug;
    private $security;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug, Security $security)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->markdownLogger = $markdownLogger;
        $this->isDebug = $isDebug;
        $this->security = $security;
    }

    public function parse(string $source): string
    {
        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }
        
        $item = $this->cache->getItem('markdown_' . md5( $source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}
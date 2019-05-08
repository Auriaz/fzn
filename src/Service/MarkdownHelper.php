<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $markdownLogger;
    private $isDebug;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger, bool $isDebug)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->markdownLogger = $markdownLogger;
        $this->isDebug = $isDebug;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false) {
            $this->markdownLogger->info('They are talking about bacon again!');
        }

        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }
        
        $item = $this->cache->getItem('markdown_' . md5( $source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform( $source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}
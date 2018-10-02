<?php

namespace App\Service;

use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;

    /**
     * MarkdownHelper constructor.
     *
     * @param AdapterInterface  $cache
     * @param MarkdownInterface $markdown
     * @param LoggerInterface   $markdownLogger
     */
    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $markdownLogger)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $markdownLogger;
    }

    public function parse(string $source): string
    {
        if (false !== stripos($source, 'bacon')) {
            $this->logger->info('They are talking about bacon again!');
        }

        // Put element on cache system
        $item = $this->cache->getItem('markdown_'.md5($source));

        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        return $item->get();
    }
}

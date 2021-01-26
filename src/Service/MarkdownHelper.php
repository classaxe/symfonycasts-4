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
     * @return mixed
     */
    public function __construct(
        MarkdownInterface $markdown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger
    ) {
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->markdown = $markdown;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false) {
            $this->logger->info('They are talking about bacon again!');
        }

        $item = $this->cache->getItem('markdown_' .md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }
        return $item->get();
    }
}
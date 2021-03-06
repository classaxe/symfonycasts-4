<?php


namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;
    /** @var Security */
    private $security;

    /**
     * @param MarkdownInterface $markdown
     * @param AdapterInterface $cache
     * @param LoggerInterface $markdownLogger
     * @param Security $security
     */
    public function __construct(
        MarkdownInterface $markdown,
        AdapterInterface $cache,
        LoggerInterface $markdownLogger,
        Security $security
    ) {
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->markdown = $markdown;
        $this->security = $security;
    }

    public function parse(string $source): string
    {
        if (stripos($source, 'bacon') !== false) {
            $this->logger->info(
                'They are talking about bacon again!',
                [ 'user' => $this->security->getUser()]
            );
        }

        $item = $this->cache->getItem('markdown_' .md5($source));
        if (!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }
        return $item->get();
    }
}
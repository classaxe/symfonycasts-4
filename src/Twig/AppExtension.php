<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    /**
     * @var MarkdownHelper
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        // Done here to avoid a performance hit due to dependency injection always instantiating whenever twig is used,
        // even if these extensions are NOT used.  Otherwise normal autowired DI would be fine.
        $this->container = $container;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'cached_markdown',
                [$this, 'processMarkdown'],
                ['is_safe' => ['html']]
            ),
        ];
    }



    public function processMarkdown($value)
    {
        return $this->container
            ->get(MarkdownHelper::class)
            ->parse($value);
    }

    /**
     * @return array
     */
    public static function getSubscribedServices(): array
    {
        return [
            MarkdownHelper::class
        ];
    }
}

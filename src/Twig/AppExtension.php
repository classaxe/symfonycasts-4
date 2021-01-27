<?php

namespace App\Twig;

use App\Service\MarkdownHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var MarkdownHelper
     */
    private $markdownHelper;

    public function __construct(MarkdownHelper $markdownHelper)
    {

        $this->markdownHelper = $markdownHelper;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe' => ['html']]),
        ];
    }



    public function processMarkdown($value)
    {
        return $this->markdownHelper->parse($value);
    }
}

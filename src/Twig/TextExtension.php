<?php

declare(strict_types=1);

namespace Bolt\Twig;

use Bolt\Common\Str;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Text functionality Twig extension.
 */
class TextExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        $safe = [
            'is_safe' => ['html'],
        ];

        return [
            new TwigFilter('safestring', [$this, 'safeString'], $safe),
            new TwigFilter('plaintext', [$this, 'plainText'], $safe),
            new TwigFilter('slug', [$this, 'slug']),
            new TwigFilter('ucwords', [$this, 'ucwords']),
            new TwigFilter('preg_replace', [$this, 'pregReplace']),
        ];
    }

    public function safeString(string $str, bool $strict = false, string $extrachars = ''): string
    {
        return Str::makeSafe($str, $strict, $extrachars);
    }

    /**
     * Returns a plaintext version of a string. Kinda like `|striptags` only with `is_safe => html`
     */
    public function plainText(string $str): string
    {
        return strip_tags($str);
    }

    public function slug(string $str): string
    {
        return Str::slug((string) $str);
    }

    public function ucwords(string $string, string $delimiters = ''): string
    {
        if (! $string) {
            return '';
        }

        return ucwords($string, $delimiters);
    }

    /**
     * Perform a regular expression search and replace on the given string.
     */
    public function pregReplace(string $str, string $pattern, string $replacement = '', int $limit = -1): string
    {
        return preg_replace($pattern, $replacement, $str, $limit);
    }
}

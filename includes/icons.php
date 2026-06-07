<?php

declare(strict_types=1);

function appIcon(string $name, string $class = 'w-5 h-5', string $label = ''): string
{
    static $cache = [];

    if (!isset($cache[$name])) {
        $path = __DIR__ . '/../public/assets/icons/' . $name . '.svg';
        if (!is_file($path)) {
            return '';
        }
        $cache[$name] = (string) file_get_contents($path);
    }

    $svg = $cache[$name];
    $aria = $label !== '' ? ' role="img" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '"' : ' aria-hidden="true"';

    if (str_contains($svg, 'class="')) {
        $svg = preg_replace('/class="([^"]*)"/', 'class="$1 ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"', $svg, 1) ?: $svg;
        return preg_replace('/<svg\b/', '<svg' . $aria, $svg, 1) ?: $svg;
    }

    return preg_replace('/<svg\b/', '<svg class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"' . $aria, $svg, 1) ?: $svg;
}

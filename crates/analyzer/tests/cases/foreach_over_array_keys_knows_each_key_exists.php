<?php

declare(strict_types=1);

/**
 * @param array<string, string> $labels
 */
function concat_labels(array $labels): string
{
    $out = '';
    foreach (array_keys($labels) as $key) {
        $out .= $labels[$key];
    }

    return $out;
}

/**
 * @param array<string, string> $labels
 * @param array<string, string> $replacement
 */
function concat_after_replacing_the_array(array $labels, array $replacement): string
{
    $out = '';
    foreach (array_keys($labels) as $key) {
        $labels = $replacement;
        $out .= $labels[$key]; // @mago-expect analysis:possibly-undefined-string-array-index
    }

    return $out;
}

/**
 * @param array<string, string> $labels
 */
function concat_other_key(array $labels, string $other): string
{
    $out = '';
    foreach (array_keys($labels) as $key) {
        $key = $other;
        $out .= $labels[$key]; // @mago-expect analysis:possibly-undefined-string-array-index
    }

    return $out;
}

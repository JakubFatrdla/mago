<?php

declare(strict_types=1);

namespace ArrayFillKeysOverLiteralKeysIsAShape;

/**
 * @param 'under'|'fair'|'over' $category
 */
function count_in(string $category): int
{
    $counts = array_fill_keys(['under', 'fair', 'over'], 0);
    $counts[$category]++;

    return $counts['fair'];
}

/**
 * @param list<string> $keys
 */
function count_open(array $keys, string $key): int
{
    $counts = array_fill_keys($keys, 0);

    return $counts[$key]; // @mago-expect analysis:possibly-undefined-string-array-index
}

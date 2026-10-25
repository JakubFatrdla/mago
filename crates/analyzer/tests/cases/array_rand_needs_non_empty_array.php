<?php

declare(strict_types=1);

namespace ArrayRandNeedsNonEmptyArray;

/**
 * @param array<string, int> $map
 */
function maybe_empty(array $map): string
{
    // @mago-expect analysis:possibly-invalid-argument
    return array_rand($map);
}

/**
 * @param non-empty-array<string, int> $map
 */
function non_empty(array $map): string
{
    return array_rand($map);
}

/**
 * @param non-empty-array<string, int> $map
 *
 * @return string|array<string>
 */
function any_count(array $map, int $count): string|array
{
    // @mago-expect analysis:possibly-invalid-argument
    return array_rand($map, $count);
}

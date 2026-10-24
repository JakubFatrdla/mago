<?php

declare(strict_types=1);

namespace ReadOffsetNullDoesNotOpenWrittenShape;

/**
 * @param list<array{from: int, to: int}> $ranges
 * @return list<array{from: int, to: int}>
 */
function merge(array $ranges): array
{
    $merged = [];
    $current = $ranges[0]; // @mago-expect analysis:possibly-undefined-int-array-index
    $current['to'] = 1;
    $merged[] = $current;

    return $merged;
}

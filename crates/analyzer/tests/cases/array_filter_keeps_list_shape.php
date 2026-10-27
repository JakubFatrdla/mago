<?php

namespace Fixture;

/** @param array{0?: int, 1?: int} $pair */
function takes_optional_pair(array $pair): int
{
    return count($pair);
}

// `array_filter()` keeps a list shape's elements as optional keys, like PHPStan:
// the filter can leave a gap, so any element may be missing.
function filtered_pair(int $first, int $second): int
{
    return takes_optional_pair(array_filter([$first, $second]));
}

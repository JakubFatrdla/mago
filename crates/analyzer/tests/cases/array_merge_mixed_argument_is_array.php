<?php

namespace Fixture;

/** @param array{currency: int, ...<string, mixed>} $filter */
function takes_filter(array $filter): int
{
    return $filter['currency'];
}

// PHPStan merges a `mixed` argument as an array of unknown keys: the known
// string keys stay, and their values may be overwritten.
function merged_with_mixed(mixed $extra, int $currency): int
{
    $filter = ['currency' => $currency];
    // @mago-expect analysis:mixed-argument
    $merged = array_merge($filter, $extra);

    // @mago-expect analysis:less-specific-nested-argument-type
    return takes_filter($merged);
}

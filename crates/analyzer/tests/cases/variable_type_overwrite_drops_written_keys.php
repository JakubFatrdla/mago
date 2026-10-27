<?php

namespace Fixture;

// shipmonk compares the previous type without the keys written into it, which
// PHPStan carries as `hasOffsetValue` accessories of the general array.
/** @param array<array-key, mixed> $extra */
function merged_filter(array $extra, int $currency): int
{
    /** @var array<string, mixed> $filter */
    $filter = [];
    $filter['currency'] = $currency;
    // @mago-expect analysis:shipmonk-variable-type-overwritten
    $filter = array_merge($filter, $extra);

    return count($filter);
}

/** @param array<string, mixed> $extra */
function merged_string_keys(array $extra, int $currency): int
{
    /** @var array<string, mixed> $filter */
    $filter = [];
    $filter['currency'] = $currency;
    $filter = array_merge($filter, $extra);

    return count($filter);
}

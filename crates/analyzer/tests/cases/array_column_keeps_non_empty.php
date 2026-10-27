<?php

namespace Fixture;

/** @param non-empty-array<array-key, mixed> $values */
function takes_non_empty(array $values): int
{
    return count($values);
}

// `array_column()` of a non-empty array whose elements all hold the column stays non-empty, like PHPStan.
/** @param non-empty-list<array{price: float, note?: string}> $items */
function column_in_every_element(array $items): int
{
    return takes_non_empty(array_column($items, 'price'));
}

/** @param non-empty-list<array{price: float, note?: string}> $items */
function optional_column(array $items): int
{
    // @mago-expect analysis:possibly-invalid-argument
    return takes_non_empty(array_column($items, 'note'));
}

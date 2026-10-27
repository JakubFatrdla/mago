<?php

namespace Fixture;

/** @param non-empty-array<array-key, mixed> $values */
function takes_non_empty(array $values): int
{
    return count($values);
}

// `array_map()` of a non-empty array that may be a list stays non-empty, like PHPStan.
/** @param non-empty-array<int, \DateTimeImmutable> $dates */
function mapped_int_keyed(array $dates): int
{
    return takes_non_empty(array_map(static fn (\DateTimeImmutable $date): int => $date->getTimestamp(), $dates));
}

/** @param array<int, \DateTimeImmutable> $dates */
function mapped_maybe_empty(array $dates): int
{
    // @mago-expect analysis:possibly-invalid-argument
    return takes_non_empty(array_map(static fn (\DateTimeImmutable $date): int => $date->getTimestamp(), $dates));
}

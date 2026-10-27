<?php

declare(strict_types=1);

/** @param array<string, string>|null $m */
function nullable_map(?array $m): string
{
    // @mago-expect analysis:possibly-null-array-access
    return $m['k'];
}

/** @param array<string, string>|false $m */
function falsable_map(array|false $m): string
{
    // @mago-expect analysis:possibly-false-array-access
    return $m['k'];
}

/** @param array{a: string}|null $m */
function nullable_shape_without_the_key(?array $m): mixed
{
    // @mago-expect analysis:possibly-null-array-access
    return $m['b'];
}

/** @param array{a: string}|array{b: string}|null $m */
function nullable_shape_union(?array $m): mixed
{
    // @mago-expect analysis:possibly-null-array-access
    return $m['a'];
}

/** @param array<string, int>|int|null $m */
function map_int_or_null(array|int|null $m): mixed
{
    // @mago-expect analysis:possibly-null-array-access
    return $m['k'];
}

/** @param array<string, int>|true $m */
function map_or_true(array|true $m): mixed
{
    // @mago-expect analysis:possibly-invalid-array-access
    return $m['k'];
}

/** @param array<string, int>|string $m */
function map_or_string(array|string $m): mixed
{
    // @mago-expect analysis:possibly-undefined-string-array-index
    return $m['k'];
}

/** @param array<string, array<string, string>>|null $m */
function nested_nullable_map(?array $m): mixed
{
    // @mago-expect analysis:possibly-null-array-access
    // @mago-expect analysis:possibly-undefined-string-array-index
    return $m['x']['y'];
}

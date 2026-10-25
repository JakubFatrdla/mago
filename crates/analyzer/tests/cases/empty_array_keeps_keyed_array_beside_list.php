<?php

declare(strict_types=1);

namespace EmptyArrayKeepsKeyedArrayBesideList;

/**
 * @param list<int> $values
 */
function takes_list(array $values): void
{
    echo count($values);
}

/**
 * @param array<string, int> $map
 */
function switch_cases(int $kind, array $map): void
{
    $values = [];
    switch ($kind) {
        case 1:
            $values = $map;

            break;
        case 2:
            $values = [1];

            break;
    }

    // @mago-expect analysis:possibly-invalid-argument
    takes_list($values);
}

/**
 * @param array<string, int> $map
 */
function nested_ternary(bool $outer, bool $inner, array $map): void
{
    $values = $outer ? ($inner ? $map : [1]) : [];

    // @mago-expect analysis:possibly-invalid-argument
    takes_list($values);
}

<?php

declare(strict_types=1);

/**
 * @param list<int> $items
 *
 * @return array{}
 */
function empty_when_count_is_not_positive(array $items): array
{
    if (count($items) > 0) {
        return [];
    }

    return $items;
}

/**
 * @param list<int> $items
 *
 * @return array{}
 */
function empty_when_count_is_not_above_zero(array $items): array
{
    if (0 < count($items)) {
        return [];
    }

    return $items;
}

/**
 * @param list<int> $items
 *
 * @return array{}
 */
function empty_when_count_is_zero(array $items): array
{
    if (count($items) !== 0) {
        return [];
    }

    return $items;
}

/**
 * @param list<int>|Countable $items
 *
 * @return array{}|Countable
 */
function countable_keeps_its_array(array|Countable $items): array|Countable
{
    if (count($items) > 0) {
        return [];
    }

    return $items; // @mago-expect analysis:invalid-return-statement
}

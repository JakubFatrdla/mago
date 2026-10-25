<?php

declare(strict_types=1);

namespace LoopKeepsTypeBeforeNonNullMixed;

/**
 * @param array<mixed> $values
 *
 * @mago-expect analysis:mixed-assignment(2)
 */
function last_value_or_null(array $values): void
{
    $last = null;
    foreach ($values as $value) {
        $last = $value ?: 'empty';
    }

    if ($last) {
        echo 1;
    }
}

/**
 * @param list<int> $values
 *
 * @mago-expect analysis:mixed-assignment
 */
function while_loop(array $values, mixed $fallback): void
{
    $last = null;
    while (count($values) > 3) {
        $last = $fallback ?: 1;
        array_pop($values);
    }

    if ($last) {
        echo 1;
    }
}

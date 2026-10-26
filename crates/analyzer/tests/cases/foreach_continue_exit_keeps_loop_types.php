<?php

declare(strict_types=1);

function assigned_in_a_condition_that_continues(mixed $value): int
{
    foreach (['a', 'b'] as $type) {
        if (!($value = mt_rand(0, 1))) {
            continue;
        }
    }

    return $value + 1;
}

function narrowed_only_on_one_path(mixed $value): mixed
{
    foreach (['a', 'b'] as $type) {
        if (!is_array($value)) {
            continue;
        }
    }

    // @mago-expect analysis:mixed-array-access
    return $value['x'];
}

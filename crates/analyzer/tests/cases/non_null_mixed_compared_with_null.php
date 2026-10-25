<?php

declare(strict_types=1);

namespace NonNullMixedComparedWithNull;

/**
 * @mago-expect analysis:redundant-comparison
 * @mago-expect analysis:redundant-logical-operation
 */
function checked_twice(mixed $value): void
{
    if ($value === null || $value === null) {
        return;
    }

    echo 1;
}

/**
 * @mago-expect analysis:redundant-comparison
 */
function checked_in_outer_condition(mixed $value): void
{
    if ($value !== null) {
        if ($value === null) {
            echo 1;
        }
    }
}

/**
 * @mago-expect analysis:redundant-comparison
 */
function always_not_null_after_return(mixed $value): void
{
    if ($value === null) {
        return;
    }

    if ($value !== null) {
        echo 1;
    }
}

function possibly_null(mixed $value): void
{
    if ($value === null) {
        echo 1;
    }

    if (null !== $value) {
        echo 2;
    }
}

<?php

declare(strict_types=1);

namespace NegatedAssertionStoresNoOffsetRead;

function take(mixed $_value): void
{
}

function read_after_if(mixed $row, array $seen): void
{
    // @mago-expect analysis:mixed-array-access
    if (in_array($row['url'], $seen, true)) {
        take(1);
    }

    // @mago-expect analysis:mixed-array-access
    take($row['url']);
}

function read_in_else(mixed $row, array $seen): void
{
    // @mago-expect analysis:mixed-array-access
    if (in_array($row['url'], $seen, true)) {
        take(1);
    } else {
        // @mago-expect analysis:mixed-array-access
        take($row['url']);
    }
}

function read_in_then(mixed $row, array $seen): void
{
    // @mago-expect analysis:mixed-array-access
    if (in_array($row['url'], $seen, true)) {
        take($row['url']);
    }
}

function read_after_isset(mixed $row): void
{
    // @mago-expect analysis:mixed-array-access
    if (isset($row['url'])) {
        take($row['url']);
    }
}

<?php

namespace Fixture;

final class Row
{
    public string $name = '';
}

// A loop that may not run links a variable it makes truthy to one it sets
// non-null, like PHPStan's conditional expressions after the merge.
/** @param list<Row> $rows */
function last_name(array $rows): string
{
    $names = [];
    $last = null;
    foreach ($rows as $row) {
        $names[] = $row->name;
        $last = $row;
    }

    if (!$names) {
        return '';
    }

    return $last->name;
}

/** @param list<Row|null> $rows */
function last_may_be_null(array $rows): string
{
    $names = [];
    $last = null;
    foreach ($rows as $row) {
        $names[] = 1;
        $last = $row;
    }

    if (!$names) {
        return '';
    }

    // @mago-expect analysis:possibly-null-property-access
    return $last->name;
}

/** @param list<Row> $rows */
function last_without_witness(array $rows): string
{
    $last = null;
    foreach ($rows as $row) {
        $last = $row;
    }

    // @mago-expect analysis:possibly-null-property-access
    return $last->name;
}

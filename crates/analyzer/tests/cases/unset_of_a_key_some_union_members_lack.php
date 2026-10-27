<?php

declare(strict_types=1);

/**
 * @param array{a: int, b: int}|array{b: int} $row
 */
function member_lacks_string_key(array $row): int
{
    unset($row['a']);

    return $row['b'];
}

/**
 * @param array{0: int, 1: int}|array{1: int} $row
 */
function member_lacks_int_key(array $row): int
{
    unset($row[0]);

    return $row[1];
}

/**
 * @param list<array{a: int, b: int}|null> $rows
 */
function null_row_written_in_the_loop(array $rows): int
{
    $count = 0;
    foreach ($rows as $row) {
        $row['x'] = true;
        unset($row['a']);
        $count += count($row);
    }

    return $count;
}

/**
 * @param array{b: int} $row
 */
function no_member_has_the_key(array $row): int
{
    unset($row['a']); // @mago-expect analysis:undefined-string-array-index

    return $row['b'];
}

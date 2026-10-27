<?php

declare(strict_types=1);

/**
 * @param non-empty-list<array{a: string}> $rows
 */
function value_variable_already_holds_the_element_type(array $rows): string
{
    $row = $rows[0];
    $out = '';
    foreach ($rows as $row) {
        $out .= $row['a'];
        unset($row['a']);
        $row['b'] = true;
    }

    return $out;
}

/**
 * @param non-empty-array<int, array{a: string}> $rows
 */
function key_variable_already_holds_the_key_type(array $rows, int $key): int
{
    $total = 0;
    foreach ($rows as $key => $row) {
        $total += $key;
        $key = 'x';
    }

    return $total;
}

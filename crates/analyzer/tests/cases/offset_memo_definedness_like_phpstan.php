<?php

declare(strict_types=1);

/**
 * @param list<int> $keys
 * @param array<array-key, string> $data
 */
function isset_and_read_then_fall_through(array $keys, array $data): string
{
    $out = '';
    foreach ($keys as $key) {
        if (isset($data[$key]) && $data[$key] === 'x') {
            continue;
        }

        // @mago-expect analysis:possibly-undefined-int-array-index
        $out .= $data[$key];
    }

    return $out;
}

/** @param array<array-key, mixed> $data */
function isset_without_else_then_read(array $data, string $key): mixed
{
    if (isset($data[$key])) {
        echo 1;
    }

    // @mago-expect analysis:possibly-undefined-string-array-index
    return $data[$key];
}

/** @param array<array-key, mixed> $data */
function isset_guard_then_read(array $data, string $key): mixed
{
    if (!isset($data[$key])) {
        return null;
    }

    return $data[$key];
}

/** @param array<array-key, array{from?: string, to: string}> $rows */
function nested_isset_proves_the_parent(array $rows, int $i): ?string
{
    if (isset($rows[$i]['from'])) {
        return $rows[$i]['to'];
    }

    return null;
}

/** @param array<array-key, array{from?: string, to: string}> $rows */
function nested_empty_then_read(array $rows, int $i): string
{
    if (empty($rows[$i]['from'])) {
        echo 1;
    }

    // @mago-expect analysis:possibly-undefined-int-array-index
    return $rows[$i]['to'];
}

/** @param array<array-key, array{from?: string, to: string}> $rows */
function nested_not_isset_then_read(array $rows, int $i): string
{
    if (!isset($rows[$i]['from'])) {
        echo 1;
    }

    // @mago-expect analysis:possibly-undefined-int-array-index
    return $rows[$i]['to'];
}

/** @param array<string, array{b: float}> $totals */
function coalesce_assignment_defines_the_key(array $totals, string $key): float
{
    $totals[$key] ??= ['b' => 0.0];

    return $totals[$key]['b'];
}

/** @param array<string, string> $row */
function compared_key_is_defined(array $row, string $column): string
{
    // @mago-expect analysis:possibly-undefined-string-array-index
    if ($row[$column] === '') {
        return '';
    }

    return $row[$column];
}

/** @param array<string, array<string, mixed>> $offer */
function false_isset_keeps_the_parent_unknown(array $offer): mixed
{
    if (!isset($offer['rooms']['room'])) {
        // @mago-expect analysis:possibly-undefined-string-array-index
        return $offer['rooms'];
    }

    return null;
}

/** @param array<string, string> $settings */
function switch_over_a_string_element_defines_it(array $settings): string
{
    switch ($settings['type']) {
        case 'a':
            return 'a';
        default:
            return 'x' . $settings['type'];
    }
}

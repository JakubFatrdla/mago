<?php

declare(strict_types=1);

/**
 * @param list<int> $ids
 * @return non-empty-list<int>
 */
function two_keys_then_a_loop_that_continues(array $ids): array
{
    $types = [];
    foreach ($ids as $id) {
        if ($id > 1) {
            $types['a'][] = $id;
        } else {
            $types['b'][] = $id;
        }
    }

    $out = [];
    foreach ($types as $type => $_block) {
        if ($type === 'a') {
            $out[] = 0;
            continue;
        }
        $out[] = 1;
    }

    // @mago-expect analysis:invalid-return-statement
    return $out;
}

/**
 * @param list<int> $ids
 * @return non-empty-list<int>
 */
function two_keys_then_a_plain_loop(array $ids): array
{
    $types = [];
    foreach ($ids as $id) {
        if ($id > 1) {
            $types['a'][] = $id;
        } else {
            $types['b'][] = $id;
        }
    }

    $out = [];
    foreach ($types as $block) {
        $out[] = count($block);
    }

    // @mago-expect analysis:invalid-return-statement
    return $out;
}

function one_of_three_shapes_or_empty(?int $from, ?int $to): int
{
    $where = [];
    if ($from !== null && $to !== null) {
        $where = ['between' => [$from, $to]];
    } elseif ($from !== null) {
        $where = ['from' => $from];
    } elseif ($to !== null) {
        $where = ['to' => $to];
    }

    if ($where) {
        return count($where);
    }

    return 0;
}

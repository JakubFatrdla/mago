<?php

declare(strict_types=1);

final class Unit
{
    public string $type = '';
}

function either_flag_narrows_the_key(string $key, bool $ok): void
{
    $keys = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
    $first = in_array($key, ['a', 'b'], true) && $ok;
    $second = in_array($key, ['c', 'd'], true) && $ok;

    if (($first || $second) && isset($keys[$key])) { // @mago-expect analysis:redundant-isset-check
        echo $keys[$key];
    }
}

function elseif_after_a_third_flag(string $key, Unit $unit): void
{
    $keys = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
    $bed = in_array($key, ['x', 'y'], true) && in_array($unit->type, ['bed'], true);
    $equipment = in_array($key, ['a', 'b'], true) && in_array($unit->type, ['bed'], true);
    $bathroom = in_array($key, ['c', 'd'], true) && $unit->type === 'bath';

    if ($bed) {
        echo 1;
    } elseif (($equipment || $bathroom) && isset($keys[$key])) { // @mago-expect analysis:redundant-isset-check
        echo $keys[$key];
    }
}

function one_flag_narrows_docblock_types_only(string $key, bool $ok): void
{
    $keys = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
    $first = in_array($key, ['a', 'b'], true) && $ok;

    if ($first && isset($keys[$key])) {
        echo $keys[$key];
    }
}

function an_operand_that_implies_nothing(string $key, bool $ok): void
{
    $keys = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
    $first = in_array($key, ['a', 'b'], true) && $ok;
    $second = $ok;

    if (($first || $second) && isset($keys[$key])) {
        echo $keys[$key];
    }
}

function a_flag_written_again(string $key, bool $ok): void
{
    $keys = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
    $first = in_array($key, ['a', 'b'], true) && $ok;
    $second = in_array($key, ['c', 'd'], true) && $ok;
    $first = $ok;

    if (($first || $second) && isset($keys[$key])) {
        echo $keys[$key];
    }
}

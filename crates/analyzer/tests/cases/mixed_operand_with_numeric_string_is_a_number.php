<?php

declare(strict_types=1);

/**
 * @param numeric-string $days
 */
function mixed_divided_by_numeric_string(mixed $amount, string $days): float|int
{
    // @mago-expect analysis:mixed-operand
    $daily = $amount / $days;

    return $daily * 2;
}

function mixed_minus_numeric_literal(mixed $amount): float|int
{
    // @mago-expect analysis:mixed-operand
    $rest = $amount - '5';

    return $rest * 2;
}

/**
 * @param numeric-string $divisor
 */
function mixed_modulo_numeric_string(mixed $amount, string $divisor): int
{
    // @mago-expect analysis:mixed-operand
    return $amount % $divisor;
}

function mixed_minus_non_numeric_string(mixed $amount, string $text): mixed
{
    // @mago-expect analysis:mixed-operand,mixed-assignment
    $rest = $amount - $text;

    // @mago-expect analysis:mixed-operand
    return $rest * 2;
}

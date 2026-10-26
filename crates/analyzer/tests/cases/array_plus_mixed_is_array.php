<?php

declare(strict_types=1);

function sum_then_add(mixed $a, mixed $b, mixed $c): void
{
    $sum = $a + $b + $c; // @mago-expect analysis:mixed-operand(3)
    $sum += 1; // @mago-expect analysis:possibly-invalid-operand
}

/**
 * @param array<string, int> $known
 *
 * @return array<array-key, mixed>
 */
function merge_unknown(array $known, mixed $unknown): array
{
    return $known + $unknown; // @mago-expect analysis:mixed-operand
}

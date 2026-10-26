<?php

declare(strict_types=1);

function closure_return_needs_number(int|float $number): void
{
}

/**
 * @param array<string, list<string>> $groups
 */
function closure_return_reduce_without_initial(array $groups): int|float|null
{
    return array_reduce($groups, fn ($carry, array $items) => ($carry ??= 0) + count($items));
}

/**
 * @param array<string, list<string>> $groups
 */
function closure_return_reduce_carry_may_be_null(array $groups): void
{
    array_reduce($groups, function ($carry, array $items): int {
        // @mago-expect analysis:possibly-null-argument
        closure_return_needs_number($carry);

        return count($items);
    });
}

/**
 * @param list<int> $numbers
 */
function closure_return_reduce_with_initial(array $numbers): int
{
    return array_reduce($numbers, fn ($carry, int $number) => $carry + $number, 0);
}

/**
 * @template T
 *
 * @param callable(T): T $callback
 *
 * @return T
 */
function closure_return_apply(callable $callback): mixed
{
    return $callback(null);
}

function closure_return_generic(): void
{
    closure_return_apply(function ($value) {
        closure_return_needs_number($value);

        return 1;
    });
}

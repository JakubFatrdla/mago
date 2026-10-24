<?php

declare(strict_types=1);

function filter_keys_of_mixed(mixed $values): array
{
    // @mago-expect analysis:mixed-argument
    // @mago-expect analysis:invalid-argument
    return array_filter($values, fn(string $key): bool => str_contains($key, '-property'), ARRAY_FILTER_USE_KEY);
}

function filter_values_of_mixed(mixed $values): array
{
    // @mago-expect analysis:mixed-argument
    // @mago-expect analysis:invalid-argument
    return array_filter($values, fn(string $value): bool => $value !== '');
}

function map_values_of_mixed(mixed $values): array
{
    // @mago-expect analysis:mixed-argument
    // @mago-expect analysis:invalid-argument
    return array_map(fn(string $value): string => $value . 'x', $values);
}

/**
 * @param array<string, string> $values
 *
 * @return array<string, string>
 */
function filter_values_of_typed_array(array $values): array
{
    return array_filter($values, fn(string $value): bool => $value !== '');
}

<?php

declare(strict_types=1);

/** @return array<string, mixed> */
function decode(mixed $value): array
{
    return [];
}

function nullable_receiver_argument(?stdClass $object): mixed
{
    // @mago-expect analysis:possibly-null-property-access
    return decode($object->x)['date'] ?? [];
}

/** @param array<string, mixed> $values */
function missing_key_argument(array $values, string $key): mixed
{
    // @mago-expect analysis:possibly-undefined-string-array-index
    return decode($values[$key])['date'] ?? [];
}

function argument_inside_isset(?stdClass $object): bool
{
    // @mago-expect analysis:possibly-null-property-access
    return isset(decode($object->x)['date']);
}

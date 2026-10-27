<?php

declare(strict_types=1);

final class Item
{
}

/** @implements ArrayAccess<string, Item> */
final class Items implements ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    public function offsetGet(mixed $offset): Item
    {
        return new Item();
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }
}

function method_of_a_nullable_container_offset(?Items $items): void
{
    // @mago-expect analysis:possibly-null-array-access
    // @mago-expect analysis:non-existent-method
    $items['x']->reset();
}

/** @param array<string, string>|false $row */
function argument_from_a_falsable_container(array|false $row): int
{
    // @mago-expect analysis:possibly-false-array-access
    return strlen($row['k']);
}

<?php

declare(strict_types=1);

namespace DocblockVarShapeMissingRequiredKey;

function shape_without_required_key(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var array{col: string, call: int} $column */
    $column = ['col' => 'a'];

    return count($column);
}

function list_of_shapes_without_required_key(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var list<array{col: string, call: int}> $columns */
    $columns = [['col' => 'a'], ['col' => 'b']];

    return count($columns);
}

function list_of_shapes_with_optional_key(): int
{
    /** @var list<array{col: string, call?: int}> $columns */
    $columns = [['col' => 'a']];

    return count($columns);
}

function sealed_shape_with_extra_key(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var array{id: int} $row */
    $row = ['id' => 1, 'other' => 2];

    return count($row);
}

function unsealed_shape_with_extra_key(): int
{
    /** @var array{id: int, ...} $row */
    $row = ['id' => 1, 'other' => 2];

    return count($row);
}

function map_with_any_keys(): int
{
    /** @var array<string, int> $row */
    $row = ['a' => 1, 'b' => 2];

    return count($row);
}

function nullable_shape_with_extra_key(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var array{id: int}|null $row */
    $row = ['id' => 1, 'other' => 2];

    return count($row ?? []);
}

function nullable_list_of_shapes_without_required_key(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var list<array{col: string, call: int}>|null $columns */
    $columns = [['col' => 'a']];

    return count($columns ?? []);
}

function nullable_shape_matched(): int
{
    /** @var array{id: int}|null $row */
    $row = ['id' => 1];

    return count($row ?? []);
}

function second_shape_matched(): int
{
    /** @var array{id: int}|array{name: string} $row */
    $row = ['name' => 'a'];

    return count($row);
}

function iterable_member_with_other_value_type(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var array{id: int}|iterable<string, string> $row */
    $row = ['id' => 1, 'other' => 2];

    return is_array($row) ? 1 : 0;
}

function iterable_member_matched(): int
{
    /** @var array{id: int}|iterable<string, int> $row */
    $row = ['id' => 1, 'other' => 2];

    return is_array($row) ? 1 : 0;
}

function callable_member_with_keyed_array(): int
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var array{id: int}|callable $row */
    $row = ['id' => 1, 'other' => 2];

    return is_array($row) ? 1 : 0;
}

function callable_member_matched(): int
{
    /** @var array{id: int}|callable $row */
    $row = ['DateTime', 'createFromFormat'];

    return is_array($row) ? 1 : 0;
}

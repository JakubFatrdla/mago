<?php

declare(strict_types=1);

namespace OverwriteWithUnspecifiedTemplateArguments;

class Row {}

final class UserRow extends Row {}

final class OrderRow extends Row {}

/**
 * @template T of Row
 */
final class Rows
{
    /**
     * @return list<T>
     */
    public function all(): array
    {
        return [];
    }

    public function count(): int
    {
        return 0;
    }
}

function filtered(Rows $rows): Rows
{
    return $rows;
}

/**
 * @return Rows<OrderRow>
 */
function orders(): Rows
{
    return new Rows();
}

/**
 * @param Rows<UserRow> $rows
 */
function without_arguments(Rows $rows): int
{
    $rows = filtered($rows);

    return $rows->count();
}

/**
 * @param Rows<UserRow> $rows
 */
function other_arguments(Rows $rows): int
{
    // @mago-expect analysis:shipmonk-variable-type-overwritten
    $rows = orders();

    return $rows->count();
}

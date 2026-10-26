<?php

declare(strict_types=1);

function mixed_operand_on_a_later_line(int $base, mixed $offset): int|float
{
    return $base // @mago-expect analysis:mixed-operand
        + $offset;
}

function nullable_operand_on_a_later_line(int $base, ?int $offset): int
{
    return $base // @mago-expect analysis:possibly-null-operand
        * $offset;
}

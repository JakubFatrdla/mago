<?php

declare(strict_types=1);

function later(mixed $value, DateTime $date): string
{
    $later = max($value, $date); // @mago-expect analysis:mixed-argument,mixed-assignment

    return $later->format('Y'); // @mago-expect analysis:mixed-method-access,mixed-return-statement
}

function earlier(mixed $value, DateTime $date): string
{
    $earlier = min($value, $date); // @mago-expect analysis:mixed-argument,mixed-assignment

    return $earlier->format('Y'); // @mago-expect analysis:mixed-method-access,mixed-return-statement
}

function larger(int $a, int $b): int
{
    return max($a, $b);
}

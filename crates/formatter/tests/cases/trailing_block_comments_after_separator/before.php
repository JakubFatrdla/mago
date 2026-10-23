<?php

$value = match ($name) {
    'a' => f(1), /** @phpstan-ignore argument.type */
    'b' => f(2), /* @phpstan-ignore argument.type */
    'c' => f(3), // @phpstan-ignore argument.type
    default => f(4), /** @phpstan-ignore argument.type */
};

$items = [
    'a' => f(1), /** @phpstan-ignore argument.type */
    'b' => f(2),
    'c' => f(3), /** @phpstan-ignore argument.type */
];

$result = g(
    f(1), /** @phpstan-ignore argument.type */
    f(2),
    f(3), /** @phpstan-ignore argument.type */
);

$last = g(
    1,
    2, /* two */
);

function parameters(
    int $a, /** @phpstan-ignore parameter.unused */
    int $b,
    int $c, /** @phpstan-ignore parameter.unused */
) {}

function last(
    int $a,
    int $b, /* b */
) {}

$leading = g(1, /* one */ 2);
$docblock = [
    1, /**
     * two
     */
    2,
];

<?php

$value = match ($name) {
    'a' => f(1), /** @phpstan-ignore argument.type */ /* why */
    'b' => f(2), /* why */ /** @phpstan-ignore argument.type */
    'c' => f(3), /** @phpstan-ignore argument.type */ // why
    'd' => f(4), /** @phpstan-ignore argument.type
     * (why) */
    default => f(5),
};
$inline = match ($name) {
    'a' => f(1), /** @phpstan-ignore argument.type */ /* why */
    'b' => f(2),
    /** @phpstan-ignore argument.type
     * (why) */ default => f(5),
};
$inlineMidLine = match ($name) {
    'a' => f(1),
    /** one */ /* two */ default => f(5),
};

$items = [
    'a' => f(1), /** @phpstan-ignore argument.type */ /* why */
    'b' => f(2), /* why */ /** @phpstan-ignore argument.type */
    'c' => f(3), /** @phpstan-ignore argument.type */ // why
    'd' => f(4), /** @phpstan-ignore argument.type
     * (why) */
    'e' => f(5),
];
$inline = [
    'a' => f(1), /** @phpstan-ignore argument.type */ /* why */
    'b' => f(2),
    /** @phpstan-ignore argument.type
     * (why) */ 'c' => f(3),
];
$inlineMidLine = [
    'a' => f(1),
    /** one */ /* two */ 'b' => f(2),
];

$result = g(
    f(1), /** @phpstan-ignore argument.type */ /* why */
    f(2), /* why */ /** @phpstan-ignore argument.type */
    f(3), /** @phpstan-ignore argument.type */ // why
    f(4), /** @phpstan-ignore argument.type
     * (why) */
    f(5), /** @phpstan-ignore argument.type
     * (why) */ /* more */
    f(6),
);
$inline = g(
    f(1), /** @phpstan-ignore argument.type */ /* why */
    f(2),
    /** @phpstan-ignore argument.type
     * (why) */ f(3),
);
$inlineMidLine = g(f(1), /** one */ /* two */ f(2));

function parameters(
    array $a, /** @phpstan-ignore missingType.iterableValue */ /* why */
    array $b, /* why */ /** @phpstan-ignore missingType.iterableValue */
    array $c, /** @phpstan-ignore missingType.iterableValue */ // why
    array $d, /** @phpstan-ignore missingType.iterableValue
     * (why) */
    int $e,
) {}

function inline(
    array $a, /** @phpstan-ignore missingType.iterableValue */ /* why */
    array $b,
    /** @phpstan-ignore missingType.iterableValue
     * (why) */ int $c,
) {}

function inlineMidLine(int $a, /** one */ /* two */ int $b) {}

$inlineDocblock = g(
    f(1), /** @phpstan-ignore argument.type
     * (why) */
    f(2),
);

function inlineDocblock(
    array $a, /** @phpstan-ignore missingType.iterableValue
     * (why) */
    int $b,
) {}

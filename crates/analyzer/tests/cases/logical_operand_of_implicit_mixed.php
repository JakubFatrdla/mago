<?php

declare(strict_types=1);

function element_of_native_array(array $a, bool $b): bool
{
    return $b && $a['x']; // @mago-expect analysis:possibly-undefined-string-array-index
}

function untyped_parameter($m, bool $b): bool
{
    return $m || $b;
}

function element_in_xor(array $a, bool $b): bool
{
    return $a['x'] xor $b; // @mago-expect analysis:possibly-undefined-string-array-index
}

function explicit_mixed(mixed $m, bool $b): bool
{
    // @mago-expect analysis:mixed-operand
    return $b && $m;
}

<?php

$share = ($part / $total) * 100;
$grouped = ($part / $total) * 100;
$redundant = $a + $b;
$required = ($a + $b) * $c;
$negated = !$value instanceof Stringable;
$cast = (float) $value / 2;
$fallback = $flag ? $items['key'] ?? [] : [];

function ratio(int $part, int $total): float
{
    return $part / $total;
}

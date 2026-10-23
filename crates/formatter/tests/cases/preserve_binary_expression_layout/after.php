<?php

$lines[] = 'Total: ' . $total . ', items: '
    . $count . ' in ' . $place;

$rows = $base + [
    'key' => $value,
];

$sql = sprintf(
    'SELECT id FROM table'
    . ' WHERE id IN (?)'
    . ' AND active',
    $id,
);

if ($first !== null
    && !isset($claimed[$first])
    && $second
) {
    return;
}

while ($left
    || $right) {
    next();
}

function check(): bool
{
    return $first !== ''
        && $second !== null;
}

$value = $first ?? $second
    ?? $third;

$flag = $a && $b || $c;

$joined = $a .
    $b;

$total = $a + $b
    + $c;

if ($ready && // is ready
    !$this->isLocked() && // is not locked
    $count > 0) {
    return;
}

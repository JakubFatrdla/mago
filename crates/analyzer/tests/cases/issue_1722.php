<?php

declare(strict_types=1);

/** @mago-expect analysis:mixed-assignment */
$items = json_decode('[]', associative: true);

$r = array_filter(
    $items, // @mago-expect analysis:mixed-argument
    static fn(array $x): bool => !empty($x['name']), // @mago-expect analysis:invalid-argument
);

var_dump($r);

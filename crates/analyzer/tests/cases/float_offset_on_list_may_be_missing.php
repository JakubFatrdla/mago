<?php

declare(strict_types=1);

function float_offset(float $factor): string
{
    $sizes = ['B', 'kB', 'MB'];

    // @mago-expect analysis:invalid-array-index
    // @mago-expect analysis:possibly-undefined-int-array-index
    return $sizes[$factor];
}

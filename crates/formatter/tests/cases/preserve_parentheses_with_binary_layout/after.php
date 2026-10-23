<?php

$visible = !$hidden && (
    $first !== null
    || $second !== null
);

$listed = $active
    && (in_array($source, $sources, true)
        || in_array($referer, $referers, true));

$total = (
    $reads * $rates['read']
    + $writes * $rates['write']
) / 1000;

$spaced = ($a
    + $b);

<?php

declare(strict_types=1);

function literal_union_key_in_a_loop(mixed $offer): mixed
{
    foreach (['a', 'b'] as $type) {
        // @mago-expect analysis:mixed-argument
        if (!array_key_exists($type, $offer)) {
            continue;
        }

        echo 1;
    }

    // @mago-expect analysis:possibly-undefined-string-array-index
    return $offer['offer'];
}

function missing_literal_key(mixed $offer): mixed
{
    // @mago-expect analysis:mixed-argument
    if (!array_key_exists('a', $offer)) {
        // @mago-expect analysis:possibly-undefined-string-array-index
        return $offer['b'];
    }

    return null;
}

function dynamic_key_leaves_mixed(mixed $offer, string $key): mixed
{
    // @mago-expect analysis:mixed-argument
    if (!array_key_exists($key, $offer)) {
        // @mago-expect analysis:mixed-array-access
        return $offer['b'];
    }

    return null;
}

<?php

declare(strict_types=1);

namespace UnionOffsetReportsMissingIndexOnce;

/**
 * @param array{a: list{'x', 'y'}, b: list{'p', 'q', 'r'}} $texts
 */
function plural(array $texts, bool $short, int $form): string
{
    $words = $short ? $texts['a'] : $texts['b'];

    return $words[$form]; // @mago-expect analysis:possibly-undefined-int-array-index
}

/**
 * @return array<int, string>
 */
function three(): array
{
    return [];
}

function destructured(): void
{
    [$a, $b] = three(); // @mago-expect analysis:possibly-undefined-int-array-index(2)
    echo $a, $b;
}

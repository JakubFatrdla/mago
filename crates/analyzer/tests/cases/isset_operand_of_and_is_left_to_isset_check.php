<?php

namespace Fixture;

// PHPStan leaves an `isset()` operand of `&&` to its isset rule, so an always
// existing offset is reported once, by the isset check.
/** @param list<int> $ids */
function index_below_count(array $ids, int $index): bool
{
    // @mago-expect analysis:redundant-isset-check
    if ($index >= 0 && $index < count($ids) && isset($ids[$index])) {
        return true;
    }

    return false;
}

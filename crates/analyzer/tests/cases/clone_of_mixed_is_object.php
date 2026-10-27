<?php

namespace Fixture;

final class Box
{
    public function size(): int
    {
        return 1;
    }
}

// PHPStan types `clone $mixed` as `object`, so a method call on the clone is a
// call on a generic object.
function cloned_mixed(mixed $value): void
{
    // @mago-expect analysis:mixed-clone
    $copy = clone $value;
    // @mago-expect analysis:ambiguous-object-method-access
    $copy->size();
}

function cloned_nullable_mixed(mixed $value, bool $copy): void
{
    $previous = null;
    if ($copy) {
        // @mago-expect analysis:mixed-clone
        $previous = clone $value;
    }

    if ($previous !== null) {
        // @mago-expect analysis:ambiguous-object-method-access
        $previous->size();
    }
}

function cloned_box(Box $box): int
{
    $copy = clone $box;

    return $copy->size();
}

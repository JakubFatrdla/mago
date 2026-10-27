<?php

namespace Fixture;

// PHPStan types a call it cannot resolve on a generic `object` as `mixed` and
// keeps checking the chain built on it.
function days_left(object $interval, \DateTimeInterface $date): int
{
    // @mago-expect analysis:ambiguous-object-method-access
    // @mago-expect analysis:mixed-property-access
    // @mago-expect analysis:mixed-operand
    // @mago-expect analysis:invalid-return-statement
    return $interval->diff($date)->days - 1;
}

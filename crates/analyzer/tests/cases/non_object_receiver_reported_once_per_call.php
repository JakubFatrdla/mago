<?php

declare(strict_types=1);

namespace NonObjectReceiverReportedOncePerCall;

/**
 * @param \DateTimeImmutable|int|true|non-empty-string $date
 */
function year(\DateTimeImmutable|int|bool|string $date): string
{
    return $date->format('Y'); // @mago-expect analysis:invalid-method-access
}

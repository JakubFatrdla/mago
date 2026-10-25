<?php

declare(strict_types=1);

namespace RememberedNullsafeReadOnNonNullReceiver;

final class Review
{
    public ?string $model = null;
}

/**
 * @mago-expect analysis:redundant-nullsafe-operator
 */
function ternary(?Review $review): ?string
{
    return is_string($review?->model) ? $review?->model : null;
}

/**
 * @mago-expect analysis:redundant-nullsafe-operator
 */
function if_body(?Review $review): ?string
{
    if ($review?->model !== null) {
        return $review?->model;
    }

    return null;
}

function receiver_still_nullable(?Review $review): ?string
{
    if ($review?->model === null) {
        return $review?->model;
    }

    return null;
}

<?php

declare(strict_types=1);

namespace TruthyMixedArrayIsNonEmpty;

/**
 * @mago-expect analysis:impossible-condition
 * @mago-expect analysis:mixed-assignment
 */
function loop_always_runs(mixed $recipients): void
{
    if (!$recipients) {
        return;
    }

    if (is_array($recipients)) {
        $none = true;
        foreach ($recipients as $recipient) {
            unset($recipient);
            $none = false;
        }

        if ($none) {
            echo 'none';
        }
    }
}

/**
 * @mago-expect analysis:mixed-assignment
 */
function possibly_empty(mixed $recipients): void
{
    if (is_array($recipients)) {
        $none = true;
        foreach ($recipients as $recipient) {
            unset($recipient);
            $none = false;
        }

        if ($none) {
            echo 'none';
        }
    }
}

function truthy_string_stays_string(mixed $value): void
{
    if (!$value) {
        return;
    }

    if (is_string($value) && $value === '') {
        echo 'empty';
    }
}

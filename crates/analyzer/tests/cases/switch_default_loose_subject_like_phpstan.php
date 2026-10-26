<?php

declare(strict_types=1);

/** @param array<string, mixed> $settings */
function default_rereads_a_mixed_subject(array $settings): string
{
    // @mago-expect analysis:possibly-undefined-string-array-index
    switch ($settings['type']) {
        case 'a':
            return 'a';
        default:
            // @mago-expect analysis:mixed-operand
            // @mago-expect analysis:possibly-undefined-string-array-index
            return 'x' . $settings['type'];
    }
}

/** @param array<string, mixed> $settings */
function loose_not_equal_rereads_a_mixed_operand(array $settings): string
{
    // @mago-expect analysis:mixed-operand
    // @mago-expect analysis:possibly-undefined-string-array-index
    if ($settings['type'] != 'a') {
        // @mago-expect analysis:mixed-operand
        // @mago-expect analysis:possibly-undefined-string-array-index
        return 'x' . $settings['type'];
    }

    return '';
}

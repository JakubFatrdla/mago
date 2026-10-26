<?php

declare(strict_types=1);

/** @param array<array-key, mixed> $rows */
function elseif_rereads_a_mixed_offset(array $rows): int
{
    // @mago-expect analysis:mixed-assignment
    foreach ($rows as $row) {
        // @mago-expect analysis:mixed-array-access
        // @mago-expect analysis:mixed-property-access
        // @mago-expect analysis:mixed-property-access
        if ($row['row']->type->rent) {
            return 1;
        }

        // @mago-expect analysis:mixed-array-access
        // @mago-expect analysis:mixed-property-access
        // @mago-expect analysis:mixed-property-access
        if ($row['row']->type->name === 'd') {
            return 2;
        }
    }

    return 0;
}

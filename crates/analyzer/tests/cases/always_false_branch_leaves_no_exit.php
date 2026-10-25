<?php

declare(strict_types=1);

namespace AlwaysFalseBranchLeavesNoExit;

/**
 * @param list<string> $lines
 *
 * @return list<string>
 */
function wrapped_items(array $lines): array
{
    $items = [];
    $html = [];
    foreach ($lines as $line) {
        if (str_starts_with($line, '- ')) {
            $items[] = substr($line, 2);

            continue;
        }

        if ($items !== []) {
            $items[count($items) - 1] .= ' ' . $line;

            continue;
        }

        $html[] = flush($items);
    }

    $html[] = flush($items);

    return $html;
}

/**
 * @param list<string> $items
 *
 * @param-out list<string> $items
 */
function flush(array &$items): string
{
    $out = implode(',', $items);
    $items = [];

    return $out;
}

/**
 * @return list<string>
 */
function first_case(int $kind, string $value): array
{
    $parts = [];
    switch ($kind) {
        case 1:
            // @mago-expect analysis:redundant-comparison
            if ($parts !== []) {
                $parts = $value;

                break;
            }

            $parts[] = $value;

            break;
    }

    return $parts;
}

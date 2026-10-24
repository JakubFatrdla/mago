<?php

declare(strict_types=1);

namespace NegatedComparisonWithUnknownValueKeepsOffsetRead;

/**
 * @param array<int, string> $a
 */
function not_identical(array $a, int $k, string $s): void
{
    // @mago-expect analysis:possibly-undefined-int-array-index
    if ($s !== $a[$k]) {
        // @mago-expect analysis:possibly-undefined-int-array-index
        echo $a[$k];
    }
}

/**
 * @param array<int, string> $a
 */
function not_equal(array $a, int $k, string $s): void
{
    // @mago-expect analysis:possibly-undefined-int-array-index
    if ($s != $a[$k]) {
        // @mago-expect analysis:possibly-undefined-int-array-index
        echo $a[$k];
    }
}

/**
 * @param array<string, mixed> $card
 * @param list<string> $names
 */
function not_in_list(array $card, array $names): void
{
    // @mago-expect analysis:possibly-undefined-string-array-index
    if (!in_array($card['name'], $names, true)) {
        // @mago-expect analysis:possibly-undefined-string-array-index
        echo serialize($card['name']);
    }
}

/**
 * @param array<int, string> $a
 */
function not_identical_to_literal(array $a, int $k): void
{
    // @mago-expect analysis:possibly-undefined-int-array-index
    if ($a[$k] !== 'x') {
        echo $a[$k];
    }
}

final class Currencies
{
    public const array CODES = [1 => 'czk', 2 => 'eur'];

    private int $seen = 0;

    /**
     * @param iterable<string> $currencies
     */
    public function count(int $id, iterable $currencies): int
    {
        $count = 0;
        // @mago-expect analysis:possibly-undefined-int-array-index
        echo self::CODES[$id];
        foreach ($currencies as $currency) {
            // @mago-expect analysis:possibly-undefined-int-array-index
            if (self::CODES[$id] !== $currency) {
                continue;
            }

            // @mago-expect analysis:possibly-undefined-int-array-index
            echo self::CODES[$id];
            $this->see();
            $count++;
        }

        return $count;
    }

    private function see(): void
    {
        $this->seen++;
    }
}

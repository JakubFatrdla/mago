<?php

declare(strict_types=1);

namespace KeyedOffsetFindingStartsAtTheAccess;

final class Feed
{
    /**
     * @return array<string, int>
     */
    public function scores(): array
    {
        return [];
    }

    public function self(): self
    {
        return $this;
    }
}

/**
 * @param array<string, int> $more
 */
function merged(array $more, string $key): int
{
    return array_merge( // @mago-expect analysis:possibly-undefined-string-array-index
        ['a' => 1],
        $more,
    )[$key];
}

function chained(Feed $feed): int
{
    return $feed // @mago-expect analysis:possibly-undefined-string-array-index
        ->self()
        ->scores()['high'];
}

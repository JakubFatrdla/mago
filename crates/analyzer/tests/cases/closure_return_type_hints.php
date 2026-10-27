<?php

namespace Fixture;

// With only `check_closure_return_type_hints` enabled, a closure without a
// return type hint is reported, while arrow functions, parameters, named
// functions and closures inside traits stay unchecked.
function run(): void
{
    $values = array_map(fn($value) => $value * 2, [1, 2, 3]);

    // @mago-expect analysis:missing-return-type
    usort($values, function ($a, $b) {
        return $a <=> $b;
    });

    usort($values, function (int $a, int $b): int {
        return $a <=> $b;
    });

    echo count($values);
}

function untyped($value)
{
    return $value;
}

trait Sorts
{
    /**
     * @param list<int> $values
     *
     * @return list<int>
     */
    public function sort(array $values): array
    {
        usort($values, function ($a, $b) {
            return $a <=> $b;
        });

        return $values;
    }
}

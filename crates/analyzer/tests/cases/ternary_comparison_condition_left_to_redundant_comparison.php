<?php

declare(strict_types=1);

namespace TernaryComparisonConditionLeftToRedundantComparison;

function label(int $count): string
{
    return $count !== null ? 'some' : 'none'; // @mago-expect analysis:redundant-comparison
}

function none(int $count): string
{
    return ($count === null) ? 'none' : 'some'; // @mago-expect analysis:redundant-comparison
}

function plain(): string
{
    $flag = true;

    return $flag ? 'a' : 'b'; // @mago-expect analysis:redundant-condition
}

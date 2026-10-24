<?php

declare(strict_types=1);

namespace UnreachableElseByDocblockIsAnalyzed;

class Table
{
    public function fetch(): self|null
    {
        return null;
    }
}

/**
 * @method Table fetch()
 */
final class NarrowTable extends Table
{
}

/**
 * @param array<string, string> $data
 */
function docblock_unreachable(NarrowTable $table, array $data): void
{
    $row = $table->fetch();
    if ($row !== null) {
        echo 'found';
    } else {
        // @mago-expect analysis:possibly-undefined-string-array-index
        echo $data['city'];
        echo $row::class;
    }
}

/**
 * @param array<string, string> $data
 */
function natively_unreachable(Table $table, array $data): void
{
    // @mago-expect analysis:redundant-condition
    if ($table instanceof Table) {
        echo 'found';
    } else {
        // @mago-expect analysis:unreachable-else-clause
        echo $data['city'];
    }
}

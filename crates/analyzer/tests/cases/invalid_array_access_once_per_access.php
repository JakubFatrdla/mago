<?php

declare(strict_types=1);

/** @param 0|1|2|array{a: int} $value */
function scalar_members_of_the_container(int|array $value): mixed
{
    // @mago-expect analysis:possibly-invalid-array-access
    return $value['a'];
}

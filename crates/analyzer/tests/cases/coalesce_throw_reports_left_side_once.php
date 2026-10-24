<?php

declare(strict_types=1);

namespace CoalesceThrowReportsLeftSideOnce;

use LogicException;

class Counter
{
    /**
     * @readonly
     */
    public int $count = 0;
}

final class Bumper extends Counter
{
    /**
     * @param \Closure(): void $bump
     * @return (\Closure(): void)|null
     */
    public function wrap(\Closure $bump): ?\Closure
    {
        return $bump;
    }

    /**
     * @throws LogicException
     */
    public function bump(): void
    {
        $bump = $this->wrap(function (): void {
            $this->count = 1; // @mago-expect analysis:possibly-invalid-property-write
            echo strlen(1); // @mago-expect analysis:invalid-argument
        }) ?? throw new LogicException('none');

        $bump();
    }
}

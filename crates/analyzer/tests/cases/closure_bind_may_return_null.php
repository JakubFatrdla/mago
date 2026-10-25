<?php

declare(strict_types=1);

final class ClosureBindHolder
{
    public bool $flag = false;

    public function bindToSelf(): void
    {
        // @mago-expect analysis:invalid-callable
        (function (): void {
            $this->flag = true;
        })->bindTo($this, self::class)();
    }

    /**
     * @param Closure(): void $callback
     */
    public function bindStatically(Closure $callback): void
    {
        $bound = Closure::bind($callback, $this, self::class);
        $bound(); // @mago-expect analysis:invalid-callable
    }

    /**
     * @param Closure(): void $callback
     */
    public function bindChecked(Closure $callback): void
    {
        $bound = $callback->bindTo($this);
        if ($bound !== null) {
            $bound();
        }
    }
}

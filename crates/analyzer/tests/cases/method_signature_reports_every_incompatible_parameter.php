<?php

declare(strict_types=1);

class EveryParameterParent
{
    public function save(int|string $key, int|string $value): void
    {
    }
}

class EveryParameterChild extends EveryParameterParent
{
    /** @mago-expect analysis:incompatible-parameter-type(2) */
    public function save(int $key, int $value): void
    {
    }
}

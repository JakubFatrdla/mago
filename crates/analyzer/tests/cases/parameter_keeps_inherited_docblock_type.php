<?php

declare(strict_types=1);

namespace ParameterKeepsInheritedDocblockType;

abstract class Provider
{
    /**
     * @param string $data
     */
    abstract protected function check($data): void;

    /**
     * @param list<int> $ids
     */
    abstract protected function load(array $ids): void;

    /**
     * @param string $value
     */
    abstract public function accept(string|int $value): void;
}

final class WideProvider extends Provider
{
    public function accept(string|int|bool $value): void
    {
    }

    protected function check(mixed $data): void
    {
        echo strlen($data);
    }

    protected function load(mixed $ids): void
    {
        foreach ($ids as $id) {
            echo $id + 1;
        }
    }
}

final class NarrowProvider extends Provider
{
    public function accept(mixed $value): void
    {
        echo strlen($value);
    }

    protected function check(mixed $data): void
    {
        echo strlen($data);
    }

    protected function load(array $ids): void
    {
        foreach ($ids as $id) {
            echo $id + 1;
        }
    }
}

function accept_int(WideProvider $provider): void
{
    $provider->accept(5);
    $provider->accept(true);
}

<?php

declare(strict_types=1);

abstract class NewStaticAbstractNode
{
    final public function __construct() {}

    public static function create(): static
    {
        return new static(); // @mago-expect analysis:abstract-instantiation
    }

    public function copy(): static
    {
        return new static();
    }

    public function copier(): Closure
    {
        return static fn(): static => new static();
    }

    public static function factory(): Closure
    {
        return static fn(): static => new static(); // @mago-expect analysis:abstract-instantiation
    }
}

final class NewStaticConcreteNode extends NewStaticAbstractNode
{
    public static function make(): static
    {
        return new static();
    }
}

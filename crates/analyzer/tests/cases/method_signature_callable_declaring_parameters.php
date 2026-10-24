<?php

declare(strict_types=1);

class CallableDeclaringParametersParent
{
    public function load(mixed $key, null|callable $generator = null, null|array $dependencies = null): mixed
    {
        return $generator === null ? $key : $generator($dependencies);
    }

    public function withMixed(null|callable $callback = null): void
    {
    }

    public function withOptional(null|callable $callback = null): void
    {
    }

    public function withVariadic(null|callable $callback = null): void
    {
    }

    public function withoutParameters(null|callable $callback = null): mixed
    {
        return $callback === null ? null : $callback();
    }

    /** @param callable(int): mixed $callback */
    public function withSignature(callable $callback): mixed
    {
        return $callback(1);
    }
}

class CallableDeclaringParametersChild extends CallableDeclaringParametersParent
{
    /**
     * @template T
     * @template D of array<string, mixed>|null
     *
     * @param (callable(D): T)|null $generator
     * @param D $dependencies
     *
     * @return ($generator is null ? mixed : T)
     *
     * @mago-expect analysis:incompatible-parameter-type(2)
     */
    public function load(mixed $key, null|callable $generator = null, null|array $dependencies = null): mixed
    {
        return $generator === null ? $key : $generator($dependencies);
    }

    /**
     * @param (callable(mixed): mixed)|null $callback
     *
     * @mago-expect analysis:incompatible-parameter-type
     */
    public function withMixed(null|callable $callback = null): void
    {
    }

    /**
     * @param (callable(int=): mixed)|null $callback
     *
     * @mago-expect analysis:incompatible-parameter-type
     */
    public function withOptional(null|callable $callback = null): void
    {
    }

    /**
     * @param (callable(int...): mixed)|null $callback
     *
     * @mago-expect analysis:incompatible-parameter-type
     */
    public function withVariadic(null|callable $callback = null): void
    {
    }

    /**
     * @template T
     *
     * @param (callable(): T)|null $callback
     *
     * @return ($callback is null ? null : T)
     */
    public function withoutParameters(null|callable $callback = null): mixed
    {
        return $callback === null ? null : $callback();
    }

    /**
     * @template T
     *
     * @param callable(int): T $callback
     *
     * @return T
     */
    public function withSignature(callable $callback): mixed
    {
        return $callback(1);
    }
}

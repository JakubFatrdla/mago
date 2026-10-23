<?php

declare(strict_types=1);

namespace CallableSignaturesInClassDocblocks;

// @mago-expect analysis:missing-callable-signature
// @mago-expect analysis:missing-callable-signature
// @mago-expect analysis:missing-callable-signature
// @mago-expect analysis:missing-callable-signature
/**
 * @method void run(callable $fn)
 * @method callable make()
 * @phpstan-type Handler callable
 * @phpstan-type Handlers array<string, callable>
 * @phpstan-type Typed callable(int): string
 */
final class Registry
{
    /**
     * @param Handler $handler
     *
     * @mago-expect analysis:missing-callable-signature
     */
    public function useAlias(mixed $handler): void
    {
    }

    /**
     * @param Typed $handler
     */
    public function useTypedAlias(mixed $handler): void
    {
    }

    /**
     * @param-out callable $handler
     *
     * @mago-expect analysis:missing-callable-signature
     */
    public function out(mixed &$handler): void
    {
        $handler = 'strlen';
    }
}

<?php

declare(strict_types=1);

/**
 * @template T
 * @template D of array<string, mixed>|null
 *
 * @param callable(D): T $generator
 * @param D $dependencies
 *
 * @return T
 */
function template_bound_load(callable $generator, null|array $dependencies = null): mixed
{
    return $generator($dependencies);
}

function template_bound_wider_parameter(): int
{
    return template_bound_load(fn (null|array $dependencies): int => count($dependencies ?? []));
}

function template_bound_narrower_parameter(): mixed
{
    // @mago-expect analysis:template-constraint-violation,invalid-argument
    return template_bound_load(fn (string $dependencies): int => strlen($dependencies));
}

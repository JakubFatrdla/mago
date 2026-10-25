<?php

declare(strict_types=1);

namespace StaticGenericReturnOnRawReceiver;

class Row
{
}

final class OfferRow extends Row
{
}

/**
 * @template T of Row
 */
class Selection
{
    /**
     * @return static<T>
     */
    public function where(): static
    {
        return clone $this;
    }

    public function order(): static
    {
        return clone $this;
    }
}

final class Repository
{
    public function raw(): Selection
    {
        return new Selection();
    }

    /**
     * @return Selection<OfferRow>
     */
    public function offers(): Selection
    {
        return new Selection();
    }
}

function generic_static_on_raw_receiver(Repository $repository): void
{
    // @mago-expect analysis:docblock-type-mismatch
    /** @var Selection<OfferRow> $selection */
    $selection = $repository->raw()->where();
    echo $selection::class;
}

function plain_static_on_raw_receiver(Repository $repository): void
{
    /** @var Selection<OfferRow> $selection */
    $selection = $repository->raw()->order();
    echo $selection::class;
}

function raw_receiver(Repository $repository): void
{
    /** @var Selection<OfferRow> $selection */
    $selection = $repository->raw();
    echo $selection::class;
}

function generic_static_on_typed_receiver(Repository $repository): void
{
    /** @var Selection<OfferRow> $selection */
    $selection = $repository->offers()->where();
    echo $selection::class;
}

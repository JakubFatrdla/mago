<?php

interface Component
{
}

/**
 * @implements ArrayAccess<string, Component>
 */
final class Form implements Component, ArrayAccess
{
    public function submit(): void
    {
    }

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    #[Override]
    public function offsetGet(mixed $offset): Component
    {
        return new Grid();
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
    }
}

final class Grid implements Component
{
}

/**
 * @implements ArrayAccess<string, Component>
 */
final class Container implements ArrayAccess
{
    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return $offset === 'form' || $offset === 'grid';
    }

    /**
     * @return ($offset is 'form' ? Form : ($offset is 'grid' ? Grid : Component))
     */
    #[Override]
    public function offsetGet(mixed $offset): Component
    {
        return $offset === 'form' ? new Form() : new Grid();
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
    }
}

function read_form(Container $container): Form
{
    $container['form']->submit();

    return $container['form'];
}

function read_grid(Container $container): Grid
{
    return $container['grid'];
}

function read_unknown(Container $container): Component
{
    return $container['other'];
}

function unset_inside_component(Container $container): void
{
    unset($container['form']['field']);
}

function read_grid_as_form(Container $container): void
{
    // @mago-expect analysis:non-existent-method
    $container['grid']->submit();
}

/**
 * @template T
 * @implements ArrayAccess<int, T>
 */
final class Rows implements ArrayAccess
{
    /** @var array<int, T> */
    private array $items = [];

    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    #[Override]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    #[Override]
    public function offsetUnset(mixed $offset): void
    {
    }
}

/** @param Rows<Form> $rows */
function read_templated_row(Rows $rows): void
{
    $rows[0]->submit();
}

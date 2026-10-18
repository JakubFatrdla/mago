<?php

declare(strict_types=1);

interface Component
{
}

final class Control implements Component
{
    public function setRequired(): void
    {
    }
}

/** @implements ArrayAccess<string, Component> */
final class Form implements ArrayAccess
{
    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    public function offsetGet(mixed $offset): Component
    {
        return new Control();
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }
}

function build(Form $form): void
{
    $form['nickname'] = new Control();
    // @mago-expect analysis:non-existent-method
    $form['nickname']->setRequired();
}

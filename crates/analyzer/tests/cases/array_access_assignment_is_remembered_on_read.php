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

final class Page
{
    private Form $form;

    public function __construct()
    {
        $this->form = new Form();
    }

    public function build(Form $form): void
    {
        $form['nickname'] = new Control();
        $form['nickname']->setRequired();

        $this->form['website'] = new Control();
        $this->form['website']->setRequired();
    }

    public function forgetOnReassign(Form $form): void
    {
        $form['nickname'] = new Control();
        $form = new Form();
        // @mago-expect analysis:non-existent-method
        $form['nickname']->setRequired();
    }

    public function forgetOnUnset(Form $form): void
    {
        $form['nickname'] = new Control();
        unset($form['nickname']);
        // @mago-expect analysis:non-existent-method
        $form['nickname']->setRequired();
    }
}

<?php

declare(strict_types=1);

namespace StaticCallable;

class Form
{
    /**
     * @var array<callable(static, array<mixed>): void>
     */
    public array $onSuccess = [];

    /**
     * @return array<mixed>
     */
    public function getValues(): array
    {
        return [];
    }
}

class LocalizedForm extends Form {}

function fire(LocalizedForm $form): void
{
    foreach ($form->onSuccess as $handler) {
        $handler($form, $form->getValues());
    }
}

function fire_with_parent(Form $form): void
{
    foreach ($form->onSuccess as $handler) {
        $handler($form, $form->getValues());
    }
}

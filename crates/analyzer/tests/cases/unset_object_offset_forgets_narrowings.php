<?php

declare(strict_types=1);

namespace UnsetObjectOffsetForgetsNarrowings;

final class Template
{
    /** @var mixed */
    public $hide;
}

/**
 * @implements \ArrayAccess<string, array<string, int>>
 */
final class Form implements \ArrayAccess
{
    public Template $template;

    public function __construct()
    {
        $this->template = new Template();
    }

    public function offsetExists(mixed $offset): bool
    {
        return true;
    }

    /**
     * @return array<string, int>
     */
    public function offsetGet(mixed $offset): array
    {
        return [];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
    }

    public function offsetUnset(mixed $offset): void
    {
    }

    public function render(): void
    {
        $this->template->hide = [];
        unset($this['form']);
        // @mago-expect analysis:mixed-array-assignment
        $this->template->hide['submit'] = true;
    }

    public function render_nested(): void
    {
        $this->template->hide = [];
        unset($this['form']['submit']);
        // @mago-expect analysis:mixed-array-assignment
        $this->template->hide['submit'] = true;
    }

    public function render_without_unset(): void
    {
        $this->template->hide = [];
        $this->template->hide['submit'] = true;
    }
}

/**
 * @param array<string, array<string, int>> $rows
 */
function unset_array_offset(array $rows): void
{
    $rows['kept'] = [];
    unset($rows['other']);
    $rows['kept']['a'] = 1;
}

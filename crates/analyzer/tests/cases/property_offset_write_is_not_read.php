<?php

namespace Fixture;

// An offset write into a private property does not read it, so a property that
// is only ever assigned, whole or by offset, is still write-only, like PHPStan.
final class Units
{
    // @mago-expect analysis:write-only-property
    private array $units = [];

    private array $read = [];

    public function build(): int
    {
        $this->units = [];
        foreach ([1, 2] as $index) {
            $this->units[$index][] = $index;
        }

        $this->read = [];
        $this->read[] = 1;

        return count($this->read);
    }
}

// An offset write into an `ArrayAccess` object calls `offsetSet()` on it, which reads the property.
final class Registry
{
    /** @var \ArrayObject<string, string> */
    private \ArrayObject $entries;

    public function __construct()
    {
        $this->entries = new \ArrayObject();
    }

    public function set(string $key, string $value): void
    {
        $this->entries[$key] = $value;
    }
}

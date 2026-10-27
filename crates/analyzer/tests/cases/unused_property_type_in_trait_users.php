<?php

namespace Fixture;

// A class using traits gets its unused property types checked like PHPStan,
// except for properties a used trait may write: by name, or any property when a
// trait writes one by a computed name.
trait ReadsByComputedName
{
    public function row(string $name): mixed
    {
        $property = $name . 'Row';

        // @mago-expect analysis:string-member-selector
        return $this->{$property};
    }
}

final class TraitReadsByComputedName
{
    use ReadsByComputedName;

    // @mago-expect analysis:unused-property-type
    private int|null $id = null;

    public function id(): int
    {
        return (int) $this->id;
    }
}

trait ReadsName
{
    public function name(): string|null
    {
        return $this->name;
    }
}

final class TraitReadsName
{
    use ReadsName;

    // @mago-expect analysis:unused-property-type
    private string|null $name = 'x';
}

trait SetsId
{
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}

trait UsesSetsId
{
    use SetsId;
}

final class NestedTraitWritesId
{
    use UsesSetsId;

    private int|null $id = null;

    public function id(): int
    {
        return (int) $this->id;
    }
}

trait WritesByComputedName
{
    public function set(string $name, int $value): void
    {
        // @mago-expect analysis:string-member-selector
        $this->{$name} = $value;
    }
}

final class TraitWritesByComputedName
{
    use WritesByComputedName;

    private int|null $id = null;

    public function id(): int
    {
        return (int) $this->id;
    }
}

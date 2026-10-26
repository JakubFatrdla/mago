<?php

declare(strict_types=1);

class ReadonlyFromConstructorBase
{
}

final class ReadonlyFromConstructorChild extends ReadonlyFromConstructorBase
{
}

final class ReadonlyFromConstructorOtherChild extends ReadonlyFromConstructorBase
{
}

function readonly_from_constructor_other_child(ReadonlyFromConstructorOtherChild $child): void
{
}

function readonly_from_constructor_child(ReadonlyFromConstructorChild $child): void
{
}

final class ReadonlyFromConstructor
{
    public function before(): void
    {
        readonly_from_constructor_child($this->base);
    }

    private readonly ReadonlyFromConstructorBase $base;

    private ReadonlyFromConstructorBase $mutable;

    private readonly ?ReadonlyFromConstructorBase $checked;

    /**
     * @throws LogicException
     */
    public function __construct(
        private readonly ReadonlyFromConstructorBase $promoted,
    ) {
        $this->base = new ReadonlyFromConstructorChild();
        $this->mutable = new ReadonlyFromConstructorChild();
        $this->checked = $promoted instanceof ReadonlyFromConstructorChild ? $promoted : null;
        if ($this->checked === null) {
            throw new LogicException();
        }
    }

    public function after(): void
    {
        readonly_from_constructor_child($this->base);
        readonly_from_constructor_child($this->checked);
        // @mago-expect analysis:less-specific-argument
        readonly_from_constructor_child($this->mutable);
        // @mago-expect analysis:less-specific-argument
        readonly_from_constructor_child($this->promoted);
    }

    public function inClosure(): Closure
    {
        return fn () => readonly_from_constructor_child($this->base);
    }

    public static function fromOutside(self $other): void
    {
        // @mago-expect analysis:less-specific-argument
        readonly_from_constructor_child($other->base);
    }
}

final readonly class ReadonlyClassFromConstructor
{
    private ReadonlyFromConstructorBase $base;

    public function __construct()
    {
        $this->base = new ReadonlyFromConstructorChild();
    }

    public function after(): void
    {
        readonly_from_constructor_child($this->base);
    }
}

final class ReadonlyFromReturningConstructor
{
    private readonly ReadonlyFromConstructorBase $base;

    public function __construct(bool $early)
    {
        if ($early) {
            $this->base = new ReadonlyFromConstructorChild();

            return;
        }

        $this->base = new ReadonlyFromConstructorOtherChild();
    }

    public function after(): void
    {
        // @mago-expect analysis:less-specific-argument
        readonly_from_constructor_other_child($this->base);
    }
}

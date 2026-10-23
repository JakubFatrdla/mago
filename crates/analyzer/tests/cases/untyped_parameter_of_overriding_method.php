<?php

declare(strict_types=1);

namespace UntypedParameterOfOverridingMethod;

class Base
{
    /**
     * @param mixed $data
     *
     * @mago-expect analysis:missing-parameter-type
     */
    public function documented($data): void
    {
    }

    /**
     * @mago-expect analysis:missing-parameter-type
     */
    public function untyped($value): void
    {
    }

    public function native(mixed $value): void
    {
    }
}

final class Child extends Base
{
    public function documented($data): void
    {
    }

    /**
     * @mago-expect analysis:missing-parameter-type
     */
    public function untyped($value): void
    {
    }

    /**
     * @mago-expect analysis:missing-parameter-type
     */
    public function native($value): void
    {
    }
}

<?php

declare(strict_types=1);

namespace OverlyWideUnionReturnWithForeignReturn;

final class Repeater
{
}

class Context
{
    public function times(bool $repeat): Repeater|static
    {
        return $repeat ? new Repeater() : $this;
    }
}

final class Document
{
    private Context $context;

    public function __construct()
    {
        $this->context = new Context();
    }

    /**
     * @mago-expect analysis:invalid-return-statement
     * @mago-expect analysis:overly-wide-return-type
     */
    public function times(bool $repeat): Repeater|static
    {
        return $this->context->times($repeat);
    }

    public function single(): Repeater
    {
        return new Repeater();
    }
}

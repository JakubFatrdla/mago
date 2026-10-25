<?php

declare(strict_types=1);

final class DefaultValueRow {}

final class DefaultValueLink
{
    /**
     * @var (callable(DefaultValueRow): (string|null))
     * @mago-expect analysis:invalid-property-default-value
     */
    private mixed $link = null;

    /** @var (callable(DefaultValueRow): (string|null))|null */
    private mixed $nullableLink = null;

    /** @var DefaultValueRow */
    private $untyped = null;

    /**
     * @var string
     * @mago-expect analysis:invalid-property-default-value
     */
    private mixed $flag = false;

    /**
     * @param callable(DefaultValueRow): (string|null) $link
     */
    public function setNullableLink(callable $link): void
    {
        $this->nullableLink = $link;
    }

    public function link(): mixed
    {
        return [$this->link, $this->nullableLink, $this->untyped, $this->flag];
    }
}

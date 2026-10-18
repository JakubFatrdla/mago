<?php

declare(strict_types=1);

final class Row
{
    public const string Url = 'url';

    /**
     * @param (ArrayAccess<string, mixed>&Traversable<string, mixed>)|array<string, mixed> $values
     *
     * @throws LogicException
     */
    public static function normalized(ArrayAccess|array $values): string
    {
        if (isset($values[self::Url])) {
            if (!is_string($values[self::Url])) {
                throw new LogicException('The url must be a string.');
            }

            $values[self::Url] = implode('/', explode('/', $values[self::Url]));

            return trim($values[self::Url], '/');
        }

        return '';
    }

    /**
     * @param (ArrayAccess<string, mixed>&Traversable<string, mixed>)|array<string, mixed> $values
     */
    public static function unwritten(ArrayAccess|array $values): string
    {
        /** @mago-expect analysis:mixed-argument,possibly-undefined-string-array-index */
        return trim($values[self::Url], '/');
    }
}

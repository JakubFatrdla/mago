<?php

declare(strict_types=1);

namespace MethodTagIsNoParentMethod;

/**
 * @method object authenticate(array{string, string} $credentials)
 * @method array<string, mixed> getData()
 */
interface Identity
{
}

final class UserIdentity implements Identity
{
    /**
     * @param array<array-key, mixed> $data
     */
    public function __construct(
        private array $data,
    ) {}

    public function authenticate(string $credentials): object
    {
        return (object) ['name' => $credentials];
    }

    public function getData(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

final class DynamicNameRow
{
    public ?int $distance = null;
}

final class DynamicNameDefaults
{
    public function __construct(
        private ?DynamicNameRow $row,
    ) {}

    /**
     * @param array<string, int> $keys
     *
     * @return array<string, mixed>
     */
    public function defaults(array $keys): array
    {
        $defaults = [];
        foreach ($keys as $key => $_) {
            if ($this->row->$key !== null) { // @mago-expect analysis:string-member-selector
                $defaults[$key] = $this->row->$key; // @mago-expect analysis:string-member-selector
            }
        }

        return $defaults;
    }
}

function dynamic_name_on_crate_read_twice(stdClass $crate, string $key): mixed
{
    if ($crate->$key !== null) {
        return $crate->$key;
    }

    return null;
}

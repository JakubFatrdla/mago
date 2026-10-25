<?php

declare(strict_types=1);

namespace NestedWriteThroughOptionalShapeKey;

final class Page
{
    /** @var array{base?: array{event: string|null, page: array{segment?: string, type?: string}}, item?: array{id?: int}} */
    public array $gtm = [];

    public function first_write(): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->gtm['base']['page']['type'] = 'blog';
    }

    public function second_write(): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->gtm['base']['page']['type'] = 'blog';
        // @mago-expect analysis:invalid-property-assignment-value
        $this->gtm['base']['page']['segment'] = 'neutral';
    }

    public function write_to_other_key(): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->gtm['base']['page']['type'] = 'blog';
        $this->gtm['item']['id'] = 1;
    }

    public function write_after_key_is_set(): void
    {
        $this->gtm['base'] = ['event' => null, 'page' => []];
        $this->gtm['base']['page']['type'] = 'blog';
        $this->gtm['base']['page']['segment'] = 'neutral';
    }

    public function write_of_all_optional_keys(): void
    {
        $this->gtm['item']['id'] = 1;
        $this->gtm['item']['id'] = 2;
    }
}

/**
 * @param array{base?: array{event: string|null, page: array{type?: string}}} $gtm
 *
 * @return array{base?: array{event: string|null, page: array{type?: string}}}
 */
function write_to_variable(array $gtm): array
{
    $gtm['base']['page']['type'] = 'blog';

    // @mago-expect analysis:invalid-return-statement
    return $gtm;
}

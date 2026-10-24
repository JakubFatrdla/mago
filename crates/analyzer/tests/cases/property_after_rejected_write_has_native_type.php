<?php

declare(strict_types=1);

namespace PropertyAfterRejectedWriteHasNativeType;

final class Settings
{
    /**
     * @var array{client: string}
     */
    private array $params;

    /**
     * @param array{google: mixed} $all
     */
    public function __construct(array $all)
    {
        // @mago-expect analysis:mixed-property-type-coercion
        $this->params = $all['google'];
        // @mago-expect analysis:possibly-undefined-string-array-index
        $this->use($this->params['client']);
    }

    /**
     * @param array<string, string> $params
     */
    public function replace(array $params): void
    {
        // @mago-expect analysis:invalid-property-assignment-value
        $this->params = $params;
        // @mago-expect analysis:possibly-undefined-string-array-index
        $this->use($this->params['client']);
    }

    private function use(mixed $value): void
    {
        echo serialize($value);
    }
}

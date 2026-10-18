<?php

declare(strict_types=1);

namespace Mago\Sdk\Analyzer;

use Mago\Sdk\Span;

/**
 * An issue an issue-filter hook removed from the file, located by its primary annotation.
 *
 * @api
 */
final class SuppressedIssue
{
    /**
     * @param non-empty-string $code
     */
    public function __construct(
        public readonly string $code,
        public readonly Span $span,
    ) {}
}

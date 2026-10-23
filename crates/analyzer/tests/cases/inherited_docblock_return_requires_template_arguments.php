<?php

declare(strict_types=1);

namespace InheritedDocblockReturnRequiresTemplateArguments;

abstract class Extension
{
    /**
     * @return list<\Generator>
     *
     * @mago-expect analysis:missing-template-parameter
     */
    public function getTags(): array
    {
        return [];
    }
}

final class Tags extends Extension
{
    /**
     * @mago-expect analysis:missing-template-parameter
     */
    public function getTags(): array
    {
        return [];
    }
}

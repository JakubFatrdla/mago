<?php

declare(strict_types=1);

namespace Mago\Tests\Sdk\Fixtures;

use Mago\Sdk\Analyzer\FileAnalysisRequirement;
use Mago\Sdk\Analyzer\NodeAnalysisContext;
use Mago\Sdk\Analyzer\NodeAnalysisHook;
use Mago\Sdk\Analyzer\Plugin;
use Mago\Sdk\Analyzer\PluginDefinition;
use Mago\Sdk\Analyzer\PluginRegistry;
use Mago\Sdk\Analyzer\Type\MixedType;
use Mago\Sdk\Extension;
use Mago\Sdk\Reporting\Issue;
use Mago\Sdk\Reporting\Level;
use Mago\Sdk\Syntax\Node;
use Mago\Sdk\Syntax\NodeKind;
use Mago\Sdk\Worker;

require_once \dirname(__DIR__, 4) . '/vendor/autoload.php';

final class ImplicitMixedPlugin implements Plugin, NodeAnalysisHook
{
    public function getDefinition(): PluginDefinition
    {
        return new PluginDefinition(
            'implicit-mixed-test',
            'Implicit mixed test',
            'Reports whether a mixed if condition is implicit.',
            defaultEnabled: true,
        );
    }

    public function register(PluginRegistry $registry): void
    {
        $registry->registerNodeAnalysisHook($this);
    }

    public function getTargets(): array
    {
        return [NodeKind::If];
    }

    public function getRequirements(): array
    {
        return [FileAnalysisRequirement::TargetSubtree];
    }

    public function analyze(NodeAnalysisContext $context): void
    {
        foreach ($context->source->getChildren($context->node) as $condition) {
            if ($condition->kind === NodeKind::Expression) {
                $this->reportMixed($context, $condition);

                return;
            }
        }
    }

    private function reportMixed(NodeAnalysisContext $context, Node $condition): void
    {
        $type = $context->analysis->getExpressionType($condition);
        if ($type === null || !$type->atomicTypes[0] instanceof MixedType) {
            return;
        }

        $code = $type->flags->implicitMixed ? 'implicit-mixed' : 'explicit-mixed';
        $context->report(Level::Note, $code, Issue::new($code, $condition->span));
    }
}

(new Worker(new Extension(
    identifier: 'mago/implicit-mixed-test',
    name: 'Mago implicit mixed extension fixture',
    version: '1.0.0',
    analyzerPlugins: [new ImplicitMixedPlugin()],
)))->run();

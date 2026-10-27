<?php

namespace Fixture;

// `DOMXPath::query()` returns `DOMNodeList<DOMNode|DOMNameSpaceNode>|false`, like
// PHPStan's stub, so an iterated node is no `mixed`.
function append_to_matches(\DOMXPath $xpath, \DOMNode $child): void
{
    $nodes = $xpath->query('//item');
    if ($nodes === false) {
        return;
    }

    foreach ($nodes as $node) {
        // @mago-expect analysis:non-existent-method
        $node->appendChild($child);
    }
}

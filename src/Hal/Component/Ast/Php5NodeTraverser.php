<?php
namespace Hal\Component\Ast;

use PhpParser\Node;
use PhpParser\NodeTraverser;

class Php5NodeTraverser extends NodeTraverser implements CustomNodeTraverser
{
    /** @var Traverser */
    private $traverser;

    /** @param callable|null $stopCondition */
    public function __construct($stopCondition = null)
    {
        parent::__construct();
        $this->traverser = new Traverser($this, $stopCondition);
    }

    public function traverseNode(Node $node)
    {
        return parent::traverseNode($node);
    }

    /** @return array */
    protected function traverseArray(array $nodes)
    {
        return $this->traverser->traverseArray($nodes, $this->visitors);
    }
}

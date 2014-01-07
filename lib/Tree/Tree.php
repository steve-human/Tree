<?php
namespace Tree;

use Tree\NestedSet\NestedSet;
use Tree\AdjacencyList\AdjacencyList;

/**
 * Abstract class which defines base properties and methods of a Tree type 
 *
 * @package    Tree
 * @license    http://opensource.org/licenses/MIT  The MIT License (MIT)
 * @author     Steve Oliveira <steve@vougalabs.com>
 */
abstract class Tree
{
    /**
     * @var Tree\Node
     */
    protected $nodes;
    
    /**
     * @const NESTED_SET
     */
    const NESTED_SET = 'NestedSet';

    /**
     * @const ADJACENCY_LIST
     */
    const ADJACENCY_LIST = 'AdjacencyList';

    /**
     * @var array a list of tree types
     */
    private $types;

    /**
     * Constructor method
     * 
     */
    public function __construct()
    {
        $this->types = array(
            'NestedSet',
            'AdjacencyList'
        );
    }
    
    /**
     * Returns a representation of this tree as a flat array
     *
     * @return array
     */
    public function toArray()
    {
        $tree = array();
        foreach ($this->nodes as $node) {
            $tree[$node->getId()] = $node->toArray();
        }
    
        return $tree;
    }
    
    /**
     * Returns node based on id passed
     *
     * @param number $id
     * 
     * @return \Tree\Tree\Node $node
     */
    public function getNode($id)
    {
        if (($this instanceof NestedSet && $id instanceof \Tree\NestedSet\Node)
        || ($this instanceof AdjacencyList && $id instanceof \Tree\AdjacencyList\Node)) 
        {
            return $id;
        } elseif (!is_numeric($id)) {
            return false;
        }

        return $this->nodes[$id];
    }

    /**
     * Returns tree nodes 
     * 
     * @return array An Array of \Tree\Tree\Node types
     */
    public function getNodes()
    {
        return $this->nodes;
    }
    
    /**
     * Set tree nodes
     * 
     * @param array $nodes An array of \Tree\Tree\Node types
     */
    public function setNodes($nodes) 
    {
        $this->nodes = $nodes;    
    }
}
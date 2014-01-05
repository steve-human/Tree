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
     * @access public
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
     * @access public
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
     * @access public
     * @return Node $node
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

    public function getNodes()
    {
        return $this->nodes;
    }
    
    public function setNodes($nodes) 
    {
        $this->nodes = $nodes;    
    }
    
    /**
     * This method is converts one type of tree to another
     *
     * @param \Tree\Tree::NESTED_SET|\Tree\Tree::ADJACENCY_LIST $type  
     * @access public
     * @return \Tree\AdjacencyList\AdjacencyList|\Tree\NestedSet\NestedSet
     */
    public function convert()
    {
        if ($this instanceof \Tree\NestedSet\NestedSet) {
            $tree = new AdjacencyList();
            $type = Tree::ADJACENCY_LIST;
        } elseif ($this instanceof \Tree\AdjacencyList\AdjacencyList) {
            $tree = new NestedSet();
            $type = Tree::NESTED_SET;
        } else {
            return false;
        }
        
        $ids = array_keys($this->nodes);
        foreach ($ids as $id) {
            $tree->nodes[$id] = new \Tree\AdjacencyList\Node($id, null);
        }
        
        return $tree;
    }
}
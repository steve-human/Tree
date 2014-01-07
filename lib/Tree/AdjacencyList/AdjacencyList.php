<?php

namespace Tree\AdjacencyList;

use Tree\Tree;
use Tree\AdjacencyList\Node;

class AdjacencyList extends Tree
{    
    /**
     * Set a new node
     *
     * @param \Tree\AdjacencyList\Node $node
     */
    public function setNode(Node $node)
    {
        $this->nodes[$node->getId()] = $node;
    }
    
    /**
     * @param array $data
     * @return Tree\Tree
     */
    public static function fromArray($data)
    {
        $tree = new self();
    
        foreach ($data as $node) {
            $tree->setNode(Node::fromArray($node));
        }
    
        return $tree;
    }

    /**
     * Calls ::convertToNestedSet
     * 
     * @return \Tree\NestedSet\NestedSet
     */
    public function convert()
    {
	    $tree = self::convertToNestedSet($this->nodes);
        $tree->sort();
        return $tree;
    }
    
    /**
     * Converts an adjacency list to a nested set
     *
     * @param array $nodes
     * @param array $parents
     * @param number $parent
     * @param number $left
     * @param number $level
     * @return number
     */
    private static function convertToNestedSet(&$nodes)
    {
        static $new_tree;
        static $parents;
        static $node;
        static $left;
        static $right;
        static $level;
        
        if ($new_tree === null) {
            $new_tree = new \Tree\NestedSet\NestedSet();
            $parents = array();
            foreach ($nodes as $node) {
                $parents[$node->getParentId()][] = $node;
            }
            reset($parents);
            $node = current($parents[0]);
            $left = 1;
            $right = 2;
            $level = 0;
        }

        $new_node = array(
            'id' => $node->getId(),
            'left' => $left,
            'level' => $level
        );
        
        // the right value of this node is the left value + 1
        $right = $left + 1;

        // get all children of this node
        if (isset($parents[$node->getId()])) {
            foreach ($parents[$node->getId()] as $node) {
                $current_id = $node->getId();
                $left = $right;
                $level += 1;
                self::convertToNestedSet($nodes);
                $right += 1;
                $new_node['right'] = $right;
            }
        }

        $new_node['right'] = $right;
        $new_tree->setNode(\Tree\NestedSet\Node::fromArray($new_node));
    
        // return the right value of this node + 1
        return $new_tree;
    }
}
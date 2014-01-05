<?php

namespace Tree\NestedSet;

use Tree\Tree;
use Tree\NestedSet\Node;

class NestedSet extends Tree
{    
    /**
     * Set a new node
     *
     * @param \Tree\NestedSet\Node $node
     * @access public
     */
    public function setNode(Node $node)
    {
        $this->nodes[$node->getId()] = $node;
    }
    
    /**
     * @param array $data
     * @static
     * @access public
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
     * Clear out all the nodes of the tree
     * 
     * @access public
     */
    public function clear()
    {
        $this->nodes = array();
    }

    /**
     * Get all of the ancestor nodes based on the supplied child node
     * 
     * @param number|\Tree\NestedSet\Node $parent
     * @param boolean $include_child
     * @access public
     * @return array 
     */
    public function getAncestors($child)
    {
        $child = $this->getNode($child);

        $ancestors = array();
        foreach ($this->nodes as $node) {
            if ($node->getLeft() < $child->getLeft() && $node->getRight() > $child->getRight()) {
                $ancestors[$node->getId()] = $node;
            }
        }
        
        if ($include_child === true) {
            $ancestors[$child->getId()] = $child;
        }
    
        return $ancestors;
    }
    
    /**
     * Get all of the descendant nodes based on the supplied parent node
     * 
     * @param number|\Tree\NestedSet\Node $parent
     * @param boolean $include_parent
     * @access public
     * @return array 
     */
    public function getDescendants($parent, $include_parent = false)
    {
        $parent = $this->getNode($parent);
           
        $descendants = array();
        
        if ($include_parent === true) {
            $descendants[$parent->getId()] = $parent; 
        }

        foreach ($this->nodes as $node) {
            if ($node->getLeft() > $parent->getLeft() && $node->getRight() < $parent->getRight()) {
                $descendants[$node->getId()] = $node;
            } 
        }
        
        return $descendants;
    }
    
    /**
     * Sort all the nodes by their left domain
     * 
     * @access public
     */
    public function sort()
    {
        uasort($this->nodes, array($this, 'sortLeft'));
    }
    
    /**
     * Return the comparison result between two nodes
     * 
     * @param \Tree\NestedSet\Node $a
     * @param \Tree\NestedSet\Node $b
     * @static
     * @access public
     * @return number
     */
    public static function sortLeft($a, $b) 
    {
        return strcmp($a->getLeft(), $b->getLeft());
    }
    
    /**
     * Return the root node
     * 
     * @access public
     * @return \Tree\NestedSet\Node
     */
    public function getRootNode()
    {
        $this->sort();
        reset($this->nodes);
        return current($this->nodes);
    }

    /**
     * Convert this nested set to an adjacency list
     * 
     * @access public
     * @return \Tree\AdjacencyList\AdjacencyList
     */
    public function convert()
    {
        $this->setLevels();
        reset($this->nodes);
        $tree = self::convertToAdjacencyList($this->nodes);
        
        return $tree;
    }
    
    /**
     * Iterate through the tree to create an adjacency list from this object
     * 
     * @param array $nodes
     * @param \Tree\NestedSet\Node $p
     * @static
     * @access private
     * @return \Tree\AdjacencyList\AdjacencyList
     */
    private static function convertToAdjacencyList(&$nodes, &$p = null)
    { 
        static $new_tree;
        
        if ($new_tree === null) {
            $new_tree = new \Tree\AdjacencyList\AdjacencyList();
        }

        while (($node = current($nodes)) !== false) {
            if (is_null($p)) {
                $parent_id = 0;
            } else {
                $parent_id = $p->getId();
            }
            
            $new_node = array(
                'id' => $node->getId(),
                'parent_id' => $parent_id,
            );
            
            $new_tree->setNode(\Tree\AdjacencyList\Node::fromArray($new_node));
            
            if (empty($nodes)) {
                break;
            }
            
            if (!$next = next($nodes)) {
                break;
            }

            if ($next->getLevel() == $node->getLevel()) {
                continue;
            }
            
            if ($next->getLevel() > $node->getLevel()) { // child
                self::convertToAdjacencyList($nodes, $node);
                ;
                if ($node = current($nodes) && $node->getLevel() <= $p->getLevel()) {
                    break;
                }
            } else {
                break;
            }
        }
        
        return $new_tree;
    }
    
    /**
     * Set the level of all the nodes in the tree
     * 
     * It is possible that some nested sets do not store the level. If this is
     * the case, we need to store it in the object in order to make use of other methods
     * such as converting the tree to an adjacency list
     * 
     * @access public
     */
    public function setLevels()
    {
        $this->sort();
        self::setNodeLevelsRecursively($this);
    }
    
    /**
     * Iterate through the tree to set the level property for each node
     * 
     * @param \Tree\NestedSet\NestedSet $tree
     * @static
     * @access private
     */
    private static function setNodeLevelsRecursively(&$tree)
    {
        static $nodes;
        static $node;
        static $level;
        
        if ($nodes === null) {
            $nodes = $tree->getNodes();
            $level = 0;
            $node = current($nodes);
        }

        if ($node->isLeaf()) {
            $node->setLevel($level);
            $tree->setNode($node);
        } else {
            $parent_node = $node;
            $nodes = $tree->getDescendants($node->getId());
            
            foreach ($nodes as $node) {
                $level += 1;
                self::setNodeLevelsRecursively($tree);
                $level -= 1;
                $parent_node->setLevel($level);
                $tree->setNode($parent_node);
            }
        }
    }
    
    /**
     * Tight a nested set's left and right domain
     * 
     * If a nested set's left and right domain's are incorrect, it is possible
     * that the tree is just "loose". All of the nodes me exist in the correct 
     * place of the hierarchy, but the left and right domain may have a range
     * larger than it should. This method will tighten and fix this issue, but only
     * if the tree's integrity is still in tact.
     * 
     * @access public
     */
    public function tighten()
    {
        $this->sort();
        $new_tree = $this->tightenRecursively($this);
    
        $this->clear();
        $this->setNodes($new_tree->getNodes());
    }
    
    /**
     * Iterate through the tree to tighten up the left and right domains
     * 
     * @param \Tree\NestedSet\NestedSet $tree
     * @static
     * @access private
     * @return \Tree\NestedSet\NestedSet
     */
    private static function tightenRecursively(&$tree = null)
    {
        static $new_tree;
        static $node;
        static $left;
        static $pointer;
        
        if ($new_tree === null) {
            $new_tree = new NestedSet();
            $node = $tree->getRootNode();
            $left = $node->getLeft();
            $pointer = 1;
        }
        
        $node = $tree->getNode($node);
        
        $new_node = array();
        
        $descendants = $tree->getDescendants($node->getId());
        $number_of_descendants = count($descendants);

        // If descendants found, we must tighten their values
        if ($number_of_descendants > 0) {
            $new_node['id'] = $node->getId();
            
            // Set the pointer to the incorrect node left + 1
            if ($node->getLeft() >= $pointer) {
                $pointer = $node->getLeft() + 1;
            }
            
            // Fix the current node's left and right values based on children
            $new_node['left'] = $left;
            if ($left == 1) { // Root node, right should be # of total nodes * 2
                $new_node['right'] = ($number_of_descendants + 1) * 2;
            } else { // Not root node
                $new_node['right'] = $new_node['left'] + (($number_of_descendants + 1) * 2) - 1;
            }
            
            // Add new node to new tree array
            $new_tree->setNode(Node::fromArray($new_node));
            
            // For each child node, set the next left to current left + 1 and repair
            foreach ($descendants as $descendant) {
                if ($pointer <= $descendant->getLeft()) {
                    $left = $left + 1;
                    $node = $descendant;
                    self::tightenRecursively($tree);
                }
            }
            // Set next left to current left + 1
            $left = $left + 1;
        } else {
            // Set the pointer to the incorrect node right + 1
            if ($node->getLeft() >= $pointer) {
                $pointer = $node->getRight() + 1;
            }
            
            // Set left/right values of new node
            $new_node['id'] = $node->getId();
            $new_node['left'] = $left;
            $new_node['right'] = $left + 1;
            
            // Set the next correct left value to left + 1
            $left = $left + 1;
            
            // Add new node to new tree array
            $new_tree->setNode(Node::fromArray($new_node));
        }
        return $new_tree;
    }
}
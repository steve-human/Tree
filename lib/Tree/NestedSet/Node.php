<?php
namespace Tree\NestedSet;

class Node extends \Tree\Node
{    
    /**
     *
     * @var number
     */
    protected $left;

    /**
     *
     * @var number
     */
    protected $right;

    /**
     * Constructor
     *
     * @param number $id
     * @param number $parent_id
     */
    public function __construct($id, $left, $right)
    {
        $this->id = $id;
        $this->left = $left;
        $this->right = $right;
    }
    
    /**
     * Returns a node from the array provided
     * 
     * @param array $data
     * 
     * @return boolean|\Tree\NestedSet\Node
     */
    public static function fromArray($data)
    {
        if (!isset($data['id'], $data['left'], $data['right'])) {
            return false;
        }
        
        $node = new self($data['id'], $data['left'], $data['right']);
        
        if (isset($data['level'])) {
            $node->setLevel($data['level']);
        }
        
        return $node;
    }
    

    /**
     * Return properties in a scalar array
     *
     * @return array
     */
    public function toArray()
    {
        $nodes = array(
            'id' => $this->getId(),
            'left' => $this->getLeft(),
            'right' => $this->getRight(),
        );
        
        $level = $this->getLevel();
        
        if ($level !== null) {
            $nodes['level'] = $this->getLevel();
        }
        
        return $nodes;
    }
    
    
    /**
     * Getter for left domain
     *
     * @return number
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Getter for right domain
     *
     * @return number
     */
    public function getRight()
    {
        return $this->right;
    }
    
    /**
     * Setter for left domain
     *
     * @param number $left
     */
    public function setLeft($left)
    {
        $this->left = $left;
    }

    /**
     * Setter for right domain
     *
     * @param number $right
     */
    public function setRight($right)
    {
        $this->right = $right;
    }
    
    /**
     * Check if this node is the root node
     *
     * @return boolean
     */
    public function isRoot()
    {
        return ($this->left == 1);
    }
    
    /**
     * Check if this node is a leaf node
     * 
     * @return boolean
     */
    public function isLeaf()
    {
        return (($this->right - $this->left) == 1);
    }
}
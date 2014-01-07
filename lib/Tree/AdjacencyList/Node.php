<?php
namespace Tree\AdjacencyList;

class Node extends \Tree\Node
{
    /**
     *
     * @var number
     */
    protected $parent_id;

    /**
     * Constructor
     *
     * @param number $id            
     * @param number $parent_id            
     */
    public function __construct($id, $parent_id)
    {
        $this->id = $id;
        $this->parent_id = $parent_id;
    }
    
    /**
     * Returns a node from the array provided
     *
     * @param array $data
     * @return boolean|\Tree\AdjacencyList\Node
     */
    public static function fromArray($data)
    {
        if (!isset($data['id'], $data['parent_id'])) {
            return false;
        }

        $node = new self($data['id'], $data['parent_id']);
    
        if (isset($data['level'])) {
            $node->setLevel($data['level']);
        }

        return $node;
    }
    
    /**
     * Return properties in a flat array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'parent_id' => $this->getParentId(),
        );
    }

    /**
     * Getter for parent Id
     *
     * @return number
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * Setter for parent_id
     *
     * @param number $parent_id            
     *
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }
}
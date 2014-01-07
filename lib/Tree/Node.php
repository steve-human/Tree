<?php
namespace Tree;

abstract class Node
{
    /**
     *
     * @var number
     */
    protected $id;
    
    /**
     * @var number
     */
    protected $level;
    
    /**
     * Getter for Id
     *
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter for id
     *
     * @param number $id
     *
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Getter for node level
     *
     * @return number
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * Setter for node level
     *
     * @param number $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}
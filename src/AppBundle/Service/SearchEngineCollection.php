<?php

namespace AppBundle\Service;

/**
 * Class SearchEngineCollection
 * 
 * @author Tamar-2
 */
class SearchEngineCollection 
{
    
    private $collection = [];
    
    public function addEngine($engine) 
    {
        $this->collection[] = $engine;
    }
    
    public function get() 
    {
        return $this->collection;
    }
}
<?php

namespace AppBundle\Service;

/**
 * Class GoogleCustomSearchClient
 * 
 * @author Tamar-2
 */
class GoogleCustomSearchClient extends SearchEngineClient 
{
    
    /**
     * Retrieve search results urls from the formatted response
     *
     * @see \AppBundle\Service\SearchEngineClient::getUrlList()
     */
    protected function getUrlList($formatted)
    {
        foreach ($formatted->items AS $item) {
           $this->addUrl($item->link);
        }
    }
}
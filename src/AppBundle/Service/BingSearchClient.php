<?php

namespace AppBundle\Service;

/**
 * Class BingSearchClient
 * 
 * @author Tamar-2
 */
class BingSearchClient extends SearchEngineClient 
{
    
    /**
     * Retrieve search results urls from the formatted response
     * 
     * @see \AppBundle\Service\SearchEngineClient::getUrlList()
     */
    protected function getUrlList($formatted)
    {
        foreach($formatted->d->results as $item) {
            $this->addUrl($item->Url);
        }
    }
}
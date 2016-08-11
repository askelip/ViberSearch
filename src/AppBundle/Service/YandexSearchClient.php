<?php

namespace AppBundle\Service;

/**
 * Class YandexSearchClient
 *
 * @author Tamar-2
 */
class YandexSearchClient extends SearchEngineClient 
{
    
    /**
     * Retrieve search results urls from the formatted response
     *
     * @see \AppBundle\Service\SearchEngineClient::getUrlList()
     */
    protected function getUrlList($formatted)
    {
        foreach($formatted->response->results->grouping->group AS $item) {
            $item = $item->children()->doc->children(); 
            $this->addUrl((string) $item->url);
        }
    }
}
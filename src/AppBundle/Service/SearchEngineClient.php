<?php

namespace AppBundle\Service;

/**
 * Abstract Class SearchEngineClient
 * 
 * @author Tamar-2
 */
abstract class SearchEngineClient 
{
    /**
     * @var Client
     */
    private $client;
    
    /**
     * @var string
     */
    private $queryParamName;
    
    /**
     * @var string
     */
    private $format;
    
    /**
     * @var bool
     */
    private $quotedQuery;
    
    /**
     * @var Array
     */
    private $urlList = [];
    
    /**
     * Format constant 
     * @var string
     */
    const JSON = 'JSON';
    
    /**
     * Format constant
     * @var string
     */
    const XML = 'XML';
    
    /**
     * Retrieve search results urls from the formatted response
     *
     * @param mixed $formatted
     */
    abstract protected function getUrlList($formatted);
    
    /**
     * Constructor
     * 
     * @param Client $client
     * @param string $queryParamName
     * @param string $format
     * @param bool $quotedQuery
     */
    public function __construct($client, $queryParamName = 'q', $format = self::JSON, $quotedQuery = false) 
    {
        $this->client = $client;
        $this->queryParamName = $queryParamName;
        $this->format = $format;
        $this->quotedQuery = $quotedQuery;
    }
    
    /**
     * Add url to the hashed urls list
     * 
     * @param string $url
     */
    protected function addUrl($url)
    {
        $this->urlList[md5($url)] = $url;
    }
    
    /**
     * Format the response
     * 
     * @param string $body
     * @return Ambigous <multitype:, SimpleXMLElement>
     */
    protected function formatResponse($body) 
    {
        $formatted = [];
        
        // TODO convert to classes and inject by constructor to allow more formats
        switch(strtoupper($this->format)) {
            case self::JSON:
                $formatted = json_decode($body);
                break;
            case self::XML:
                $formatted = simplexml_load_string($body);
                break;
        }
        
        return $formatted;
    }
    
    /**
     * Return hashed url list
     * 
     * @return \AppBundle\Service\Array
     */
    public function getSearchResultsUrls() 
    {
        return $this->urlList;
    }
    
    /**
     * Perform a GET request to client with text to query
     * Returns a formatted response by specified format
     * 
     * @param string $text
     * @return Ambigous <multitype:, SimpleXMLElement>
     */
    public function get($text) 
    {
        // create a request that has an additional query param
        $configuredQuery = $this->client->getConfig('query');
        if ($this->quotedQuery) {
            $configuredQuery[$this->queryParamName] = "'" . $text . "'";
        } else {
            $configuredQuery[$this->queryParamName] = $text;
        }
        
        $formatted = false;
        $response = $this->client->request('GET', '', ['query' => $configuredQuery]);
        $body = (string) $response->getBody();
        
        // format the response
        $formatted = $this->formatResponse($body);
        // extract urls from search results
        $this->getUrlList($formatted);

        return $formatted;
    }
}
<?php

namespace Tests\AppBundle\Service;

use GuzzleHttp\Client;
use AppBundle\Service\GoogleCustomSearchClient;


class SearchEngineTest extends \PHPUnit_Framework_TestCase
{
    
    protected $configuredClient;
    
    protected $misconfiguredClient;
    
    public function setUp() 
    {
        $client = new Client([
            'base_uri' => 'https://www.googleapis.com/customsearch/v1/',
            'query' => ['cx' => '014863322373665175340:sologvaer9e', 'key' => 'AIzaSyBdNK2jOs0WN7RHNQ7NMlXpLI-2V7YDyTE'],
        ]);
        
        $this->configuredClient = new GoogleCustomSearchClient($client);
        
        $this->misconfiguredClient = new GoogleCustomSearchClient($client, "s");
    }
    
    /**
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function testMisconfiguredRequest() 
    {
        $response = $this->misconfiguredClient->get('sushi');
    }
    
    public function testClient() 
    {
        $response = $this->configuredClient->get('sushi');
        $this->assertNotEmpty($response);
        
        $urls = $this->configuredClient->getSearchResultsUrls();
        $this->assertNotEmpty($urls);
    }
}
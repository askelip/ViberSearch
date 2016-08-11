<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QueryRepository")
 */
class Query 
{
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=400)
     */
    private $text;
    
    /**
     * @ORM\Column(type="datetime", name="created_on")
     */
    private $createdOn;
    
    /**
     * @ORM\OneToMany(targetEntity="SearchResult", mappedBy="query")
     */
    private $searchResults;
    
    /**
     * Constructor
     */
    function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->searchResults = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }   

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Query
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Query
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Add searchResult
     *
     * @param \AppBundle\Entity\SearchResult $searchResult
     *
     * @return Query
     */
    public function addSearchResult(\AppBundle\Entity\SearchResult $searchResult)
    {
        $this->searchResults[md5($searchResult->getUrl())] = $searchResult;

        return $this;
    }

    /**
     * Remove searchResult
     *
     * @param \AppBundle\Entity\SearchResult $searchResult
     */
    public function removeSearchResult(\AppBundle\Entity\SearchResult $searchResult)
    {
        $this->searchResults->removeElement($searchResult);
    }

    /**
     * Get searchResults
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSearchResults()
    {
        return $this->searchResults;
    }

}

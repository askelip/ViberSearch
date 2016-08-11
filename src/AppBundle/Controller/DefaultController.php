<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Query;
use AppBundle\Entity\SearchResult;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    
    /**
     * Search engine form retrieves results from configured search engines
     * 
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $query = new Query();
        $searchResults = [];
        
        $form = $this->createFormBuilder($query)
        ->add('text', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Search!'))
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        
            $query = $form->getData();
            $queryRepository = $this->getDoctrine()->getRepository('AppBundle:Query');
            $cachedQuery = $this->getDoctrine()
                ->getRepository('AppBundle:Query')
                ->getRecentQueryByText(
                    $query->getText(),
                    $this->getParameter('search_engine_cache_ttl_seconds')
            );
        
            if (empty($cachedQuery)) {
                // query clients
                $aggregated = [];
                $engines = $this->get('search_engines');
                foreach($engines->get() AS $engine) {
                    $response = $engine->get($query->getText());
                    $aggregated += $engine->getSearchResultsUrls();
                }
        
                // save query + results
                $em = $this->getDoctrine()->getManager();
                foreach($aggregated AS $url) {
                    $searchResult = new SearchResult();
                    $searchResult->setUrl($url);
                    $searchResult->setQuery($query);
                    $query->addSearchResult($searchResult);
                    $em->persist($searchResult);
                }
        
                $em->persist($query);
                $em->flush();
        
                $searchResults = $query->getSearchResults();
            } else {
                $searchResults = $cachedQuery->getSearchResults();
            }
        }
        
        return $this->render('default/form.html.twig', array(
            'form' => $form->createView(),
            'searchResults' => $searchResults,
        ));
    }
}

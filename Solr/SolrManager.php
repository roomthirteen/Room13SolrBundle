<?php

namespace Room13\SolrBundle\Solr;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class SolrManager
{
    /**
     * @var SolrIndex[]
     */
    private $indexes = array();


    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @var SolrService
     */
    private $service;

    /**
     * @var EntityManager
     */
    private $em;

    function __construct(SolrService $service,Container $container)
    {
        $this->service = $service;
        $this->container = $container;

    }


    public function addIndex($id, SolrIndex $index)
    {
        $this->indexes[$index->getName()]=$index;
    }

    public function getIndex($name)
    {
        return isset($this->indexes[$name]) ? $this->indexes[$name] : null;
    }

    /**
     * @param $indicies SolrIndex[]
     */
    public function setIndexes($indicies)
    {
        $this->indexes = $indicies;
    }

    /**
     * @return SolrIndex[]
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @param \Room13\SolrBundle\Solr\SolrService $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return \Room13\SolrBundle\Solr\SolrService
     */
    public function getService()
    {
        return $this->service;
    }


}

<?php

namespace Room13\SolrBundle\Solr;

use Doctrine\ORM\EntityManager;
use Room13\SolrBundle\Solr\Index;
use Room13\SolrBundle\Entity\IndexMeta;
use Room13\SolrBundle\Solr\Query;

class SolrFacade
{
    /**
     * @var SolrIndex[]
     */
    private $indexes = array();

    /**
     * @var SolrService
     */
    private $service;

    /**
     * @var EntityManager
     */
    private $em;

    function __construct(SolrService $service,EntityManager $em)
    {
        $this->service = $service;
        $this->em = $em;

    }

    public function search(Query $query,$offset=0,$limit=10)
    {
        $queryString = $query->toString();

        $response = $this->service->search($queryString,$offset,$limit);

        return new ResultSet($response,$queryString,$offset,$limit);
    }

    public function addIndex($id,  $index)
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

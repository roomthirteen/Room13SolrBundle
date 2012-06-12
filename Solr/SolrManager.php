<?php

namespace Room13\SolrBundle\Solr;

use Doctrine\ORM\EntityManager;
use Room13\SolrBundle\Solr\Index\SolrIndex;
use Room13\SolrBundle\Entity\IndexMeta;

class SolrManager
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

    /**
     * @param mixed $name SolrIndex instance of name of index as string
     * @return \Room13\SolrBundle\Entity\IndexMeta
     */
    public function getIndexMeta($index)
    {
        if(!$index instanceof SolrIndex)
        {
            // allow to pass the index name instead of the object
            $index = $this->getIndex($index);
        }

        if($index===null)
        {
            throw new \InvalidArgumentException(sprintf(
                '%s is not a valid solr index',
                $index
            ));
        }

        $repository = $this->em->getRepository('Room13\SolrBundle\Entity\IndexMeta');
        $meta       = $repository->findOneByName($index->getName());


        if($meta === null)
        {
            // the meta data has not been created yet, so we do it now
            $meta = new IndexMeta();
            $meta->setName($index->getName());

            $this->em->persist($meta);
            $this->em->flush($meta);
        }

        return $meta;
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

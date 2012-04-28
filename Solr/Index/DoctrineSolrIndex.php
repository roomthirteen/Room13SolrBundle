<?php

namespace Room13\SolrBundle\Solr\Index;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineSolrIndex extends SolrIndex
{

    /**
     * @var EntityManager
     */
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function listObjectsToUpdate()
    {
        return $this->em->getRepository($this->getType())->findAll();
    }

    public function countObjectsToUpdate()
    {
        return $this->em->createQuery("SELECT COUNT(o) FROM {$this->getType()} o")->getSingleScalarResult();
    }

    public function countObjects()
    {
        return $this->em->createQuery("SELECT COUNT(o) FROM {$this->getType()} o")->getSingleScalarResult();
    }

}

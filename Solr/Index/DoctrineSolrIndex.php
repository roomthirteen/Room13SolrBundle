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

    public function listObjectsToUpdate(\DateTime $modifiedSince)
    {
        $qb = $this->em->createQueryBuilder()
            ->from($this->getType(),'o')
            ->select('o')
            ->where('o.updated > :lastUpdate')
            ->setParameter('lastUpdate',$modifiedSince)
        ;

        return $qb->getQuery()->getResult();
    }

    public function countObjectsToUpdate(\DateTime $modifiedSince)
    {
        $qb = $this->em->createQueryBuilder()
            ->from($this->getType(),'o')
            ->select('COUNT(o)')
            ->where('o.updated > :lastUpdate')
            ->setParameter('lastUpdate',$modifiedSince)
        ;

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countObjects()
    {
        return $this->em->createQuery("SELECT COUNT(o) FROM {$this->getType()} o")->getSingleScalarResult();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

}

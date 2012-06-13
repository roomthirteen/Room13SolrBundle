<?php

namespace Room13\SolrBundle\Solr;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Room13\SolrBundle\Entity\IndexMeta;

abstract class DoctrineIndex extends Index
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

        $meta = $this->getOrCreateIndexMeta();

        $qb = $this->em->createQueryBuilder()
            ->from($this->getType(),'o')
            ->select('o')
            ->where('o.updated > :lastUpdate')
            ->setParameter('lastUpdate',$meta->getLastUpdate())
        ;

        return $qb->getQuery()->getResult();
    }

    public function countObjectsToUpdate()
    {
        $meta = $this->getOrCreateIndexMeta();

        $qb = $this->em->createQueryBuilder()
            ->from($this->getType(),'o')
            ->select('COUNT(o)')
            ->where('o.updated > :lastUpdate')
            ->setParameter('lastUpdate',$meta->getLastUpdate())
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

    /**
     * Sets the indexes meta last updated value to passed date
     *
     * @param DateTime $date
     */
    public function setIndexLastUpdated(\DateTime $date)
    {
        $meta = $this->getOrCreateIndexMeta();

        $meta->setLastUpdate($date);
        $this->em->flush($meta);

    }

    public function resetIndexLastUpdated()
    {
        $this->setIndexLastUpdated(new \DateTime('1984'));
    }


    /**
     * Returns the indexes metadata held as an entity in database. This will save when the index
     * has last been updated.
     *
     * @return \Room13\SolrBundle\Entity\IndexMeta
     * @throws \InvalidArgumentException
     */
    public function getOrCreateIndexMeta()
    {

        $repository = $this->em->getRepository('Room13\SolrBundle\Entity\IndexMeta');
        $meta       = $repository->findOneByName($this->getName());


        if($meta === null)
        {
            // the meta data has not been created yet, so we do it now
            $meta = new IndexMeta();
            $meta->setName($this->getName());
            $meta->setLastUpdate(new \DateTime('1984'));

            $this->em->persist($meta);
            $this->em->flush($meta);
        }

        return $meta;
    }

}

<?php

namespace Room13\SolrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room13\GeoBundle\Entity\IndexMeta
 *
 * @ORM\Table(name="room13_solr_index_meta")
 * @ORM\Entity
 */
class IndexMeta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var datetime $lastUpdate
     *
     * @ORM\Column(name="lastUpdate", type="datetime")
     */
    private $lastUpdate;

    /**
     * @var boolean $autoIndex
     *
     * @ORM\Column(name="autoIndex", type="boolean")
     */
    private $autoIndex;


    function __construct()
    {
        $this->lastUpdate = new \DateTime('1984');
        $this->autoIndex = false;
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
     * Set name
     *
     * @param string $name
     * @return IndexMeta
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastUpdate
     *
     * @param datetime $lastUpdate
     * @return IndexMeta
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set autoIndex
     *
     * @param boolean $autoIndex
     * @return IndexMeta
     */
    public function setAutoIndex($autoIndex)
    {
        $this->autoIndex = $autoIndex;
        return $this;
    }

    /**
     * Get autoIndex
     *
     * @return boolean 
     */
    public function getAutoIndex()
    {
        return $this->autoIndex;
    }
}
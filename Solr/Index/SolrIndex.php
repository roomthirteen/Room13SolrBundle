<?php

namespace Room13\SolrBundle\Solr\Index;

use Doctrine\ORM\EntityManager;


abstract class SolrIndex
{


    public function indexObject($object)
    {
        $doc = new \Apache_Solr_Document();

        $doc->meta_id       = $object->getId();
        $doc->meta_type     = $this->getType();
        $doc->meta_index    = $this->getName();
        $doc->meta_name     = $object->__toString();
        $doc->meta_version  = 0;

        // created and updated date
        if(is_callable(array($object,'getCreated')))
        {
            $doc->meta_created = $this->formatDate($object->getCreated());
        }

        if(is_callable(array($object,'getUpdated')))
        {
            $doc->meta_created = $this->formatDate($object->getUpdated());
        }


        foreach($this->getFields() as $name=>$type)
        {
            $this->indexField($doc,$name,$type,$object);
        }

        return $doc;
    }

    public function indexField(\Apache_Solr_Document $doc, $field,$type,$object)
    {
        $normalizedField = $this->normalizeFieldName($field);

        $indexMethod     = 'index'.ucfirst($normalizedField).'Field';
        $getterMethod    = 'get'.ucfirst($normalizedField);

        if(is_callable(array($this,$indexMethod)))
        {
            $data = $this->$indexMethod($object->$getterMethod());
            foreach($data as $key=>$value)
            {
                $doc->$key=$this->formatField($type,$value);
            }
        }
        else
        {
            $doc->{$field.'_'.$type} = $this->formatField($type,$object->$getterMethod());
        }
    }


    public function formatDate(\DateTime $date)
    {
        $date = clone($date);
        $date->setTimezone(new \DateTimeZone('UTC'));
        return $date->format('Y-m-d\\TH:i:00.00\\Z');
    }

    public function normalizeFieldName($name)
    {
        return implode('',array_map('ucfirst',explode('_',$name)));

    }

    public function formatField($type,$value)
    {
        if($value===null)
        {
            return null;
        }

        switch($type)
        {
            case 'date': return $this->formatDate($value);
            default: return $value;
        }
    }

    public abstract function getName();
    public abstract function getFields();
    public abstract function getType();
    public abstract function listObjectsToUpdate();
    public abstract function countObjectsToUpdate();
    public abstract function countObjects();

}

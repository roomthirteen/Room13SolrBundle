<?php

namespace Room13\SolrBundle\Solr;


class ResultDocument
{
    private $document;

    public function __construct(\Apache_Solr_Document $document)
    {
        $this->document = $document;
    }

    public function __get($name)
    {
        return $this->document->$name;
    }

    public function plain()
    {
        return $this->document;
    }
}

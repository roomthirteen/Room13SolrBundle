<?php

namespace Room13\SolrBundle\Solr;

class SolrService extends \Apache_Solr_Service
{

    public function clearIndex()
    {
        $this->delete('<delete><query>*:*</query></delete>');
        $this->commit(true);
    }
}

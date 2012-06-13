<?php

namespace Room13\SolrBundle\Solr;


class ResultSet implements \Iterator, \Countable
{
    /**
     * @var Apache_Solr_Response
     */
    private $result;

    private $queryString;
    private $offset;
    private $limit;

    private $wrappedResults = array();

    private $iteratorOffset=0;

    function __construct(\Apache_Solr_Response $result, $queryString, $offset, $limit)
    {
        $this->result = $result;
        $this->queryString = $queryString;
        $this->offset = $offset;
        $this->limit = $limit;


    }

    public function debugInfo()
    {
        return array(
            'query'=>$this->queryString,
            'limit'=>$this->limit,
            'offset'=>$this->offset,
            'numFound'=>$this->numFound(),
            'count'=>$this->count(),
        );
    }

    public function count()
    {
        return count($this->result->response->docs);
    }

    public function numFound()
    {
        return intval($this->result->response->numFound);

    }

    public function current()
    {
        if(!isset($this->wrappedResults[$this->iteratorOffset]))
        {
            $wrapper = new ResultDocument($this->result->response->docs[$this->iteratorOffset]);
            $this->wrappedResults[$this->iteratorOffset] = $wrapper;
        }

        return $this->wrappedResults[$this->iteratorOffset];
    }

    public function next()
    {
        $this->iteratorOffset++;
    }

    public function key()
    {
        return $this->iteratorOffset;
    }

    public function valid()
    {
        return $this->count() > 0 && $this->iteratorOffset < $this->count();
    }

    public function rewind()
    {
        $this->iteratorOffset = 0;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }
}

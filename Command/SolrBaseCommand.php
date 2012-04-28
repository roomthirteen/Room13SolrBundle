<?php

namespace Room13\SolrBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class SolrBaseCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{


   /**
    * @return \Room13\SolrBundle\Solr\SolrManager
    */
    public function getSolrManager()
    {
        return $this->getContainer()->get('room13.solr.manager');
    }

    /**
     * @return \Room13\SolrBundle\Solr\SolrService
     */
    public function getSolrService()
    {
        return $this->getContainer()->get('room13.solr.service');
    }
}

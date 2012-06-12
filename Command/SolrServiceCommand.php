<?php

namespace Room13\SolrBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SolrServiceCommand extends SolrBaseCommand
{
    public function configure()
    {

        $this
            ->setName('room13:solr:run-server')
            ->setDescription('Runs the solr server process')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $solrRoot = $this->getContainer()->getParameter('room13.solr.config.solr_root');
        $schemaRoot = $this->getContainer()->getParameter('room13.solr.config.schema_root');

        $p = popen("cd {$solrRoot} && java -Dsolr.solr.home={$schemaRoot} -jar start.jar","r");

        fpassthru($p);


    }
}

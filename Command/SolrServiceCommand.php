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
            ->setName('room13:solr:service')
            ->setDescription('Controls the solr server')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $dir = dirname(__FILE__).'/../vendor/solr/';
        $p = popen("cd {$dir} && java -jar start.jar","r");

        fpassthru($p);


    }
}

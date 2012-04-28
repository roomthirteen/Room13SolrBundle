<?php

namespace Room13\SolrBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SolrSearchCommand extends SolrBaseCommand
{
    public function configure()
    {

        $this
            ->setName('room13:solr:search')
            ->setDescription('Search the solr index')
            ->addOption('index',null,InputOption::VALUE_REQUIRED,'Restrict search to index',false)
            ->addOption('more','m',InputOption::VALUE_NONE,'Print detailed field information')
            ->addArgument('query',InputArgument::IS_ARRAY,'Solr query',array())
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $man        = $this->getSolrManager();
        $service    = $man->getService();

        $params = array();

        if($input->getOption('index'))
        {
            $index = $man->getIndex($input->getOption('index'));
            if(!$index)
            {
                throw new \Exception(sprintf(
                    "Index %s does not exist",
                    $input->getOption('index')
                ));
            }
            $params[]='meta_index:'.$input->getOption('index');
        }


        $query = implode(' AND ',array_merge($params,$input->getArgument('query')));

        $output->writeln("");
        $output->writeln("Searching for : {$query}");
        $start = time();
        $result = $service->search($query);
        $time = time() - $start;

        $output->writeln("Time          : {$time}s");
        $output->writeln("Results       : {$result->response->numFound}");
        $output->writeln("");

        foreach($result->response->docs as $doc)
        {
            if($input->getOption('more'))
            {
                $output->writeln("{$doc->meta_id} - {$doc->meta_name}");
                foreach($doc as $name=>$value)
                {
                    $output->writeln("  {$name} = {$value}");
                }
                $output->writeln("");
            }
            else
            {
                $output->writeln("{$doc->meta_id} - {$doc->meta_name} : {$doc->meta_index}");
            }

        }
        $output->writeln("");


    }
}

<?php

namespace Room13\SolrBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SolrIndexCommand extends SolrBaseCommand
{
    public function configure()
    {

        $this
            ->setName('room13:solr:index')
            ->setDescription('Update the solr index')
            ->addArgument('include',InputArgument::IS_ARRAY,'Indexes to update',array())
            ->addOption('all','a',InputOption::VALUE_NONE,'Includes all indexes to update')
            ->addOption('none','no',InputOption::VALUE_NONE,'Excludes all indexes from update')
            ->addOption('list','l',InputOption::VALUE_NONE,'Lists available indexes')
            ->addOption('clear','c',InputOption::VALUE_NONE,'Clears the whole index. Note will not be executed if --list is present.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $man        = $this->getSolrManager();
        $service    = $this->getSolrService();
        $indexes    = $man->getIndexes();
        $em         = $this->getContainer()->get('doctrine.orm.entity_manager');


        if($input->getOption('all') && $input->getOption('none'))
        {
            throw new \Exception('--all and --none options do not get along well');
        }

        if($input->getOption('all'))
        {
            $indexes = $man->getIndexes();
        }

        if($input->getOption('none'))
        {
            $indexes = array();
        }

        foreach($input->getArgument('include') as $indexToInclude)
        {
            $index = $man->getIndex($indexToInclude);

            if(!$index)
            {
                throw new \Exception(sprintf(
                    'Solr index %s not found, available indexes are: %s',
                    $indexToInclude,
                    implode(', ',array_keys($man->getIndexes()))
                ));
            }

            $indexes[]=$index;
        }

        if($input->getOption('list'))
        {
            $output->writeln("Listing available indexes\n");
            foreach($indexes as $index)
            {
                $indexMeta = $man->getIndexMeta($index);

                $output->writeln("{$index->getName()}:");
                $output->writeln("  type       : {$index->getType()}");
                $output->writeln("  count      : {$index->countObjectsToUpdate($indexMeta->getLastUpdate())}");
                $output->writeln("  updatable  : {$index->countObjects()}");
                $output->writeln("-");
            }
            return;
        }

        if(count($indexes)===0)
        {
            throw new \Exception("No indexes found. Did you specify to much filter options?");
        }


        if($input->getOption('clear'))
        {
            $output->writeln('Clearing indexes');

            foreach($indexes as $index)
            {
                $output->writeln("  {$index->getName()}");
                $service->delete("<delete><query>meta_index:{$index->getName()}</query></delete>");

                $meta = $man->getIndexMeta($index);
                $meta->setLastUpdate(new \DateTime('1984'));
                $em->flush($meta);
            }

            $service->commit();
            $service->optimize();

            return;
        }


        $output->writeln("");

        foreach($indexes as $index)
        {

            $meta       = $man->getIndexMeta($index);
            $thisUpdate = new \DateTime();
            $numResults = $index->countObjectsToUpdate($meta->getLastUpdate());
            $result     = $index->listObjectsToUpdate($meta->getLastUpdate());

            $output->writeln("Updating index {$index->getName()}, {$numResults} objects to update");

            foreach($result as $object)
            {
                $doc = $index->indexObject($object);
                $man->getService()->addDocument($doc);
            }

            // update the last updated valud of the index meta
            $meta->setLastUpdate($thisUpdate);
            $em->flush($meta);


        }

        $man->getService()->commit();
        $man->getService()->optimize();

        $output->writeln("");


    }
}

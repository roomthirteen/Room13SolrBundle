<?php

namespace Room13\SolrBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SolrCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if(!$container->hasDefinition('room13.solr'))
        {
            return;
        }

        $manager = $container->getDefinition('room13.solr');

        foreach($container->findTaggedServiceIds('room13.solr.index') as $id=>$service)
        {
            $manager->addMethodCall('addIndex',array($id,new Reference($id)));
            $container->setAlias('room13.solr.index.'.$id,$id);
        }

    }


}

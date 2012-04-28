<?php

namespace Room13\SolrBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Room13\SolrBundle\DependencyInjection\Compiler\SolrCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Room13SolrBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SolrCompilerPass());
    }
}

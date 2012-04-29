<?php

namespace Room13\SolrBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('room13_solr');

        $rootNode
            ->children()
            ->booleanNode('enabled')->defaultTrue()->end()
            ->scalarNode('host')->cannotBeEmpty()->defaultValue('localhost')->end()
            ->scalarNode('port')->cannotBeEmpty()->defaultValue('8983')->end()
            ->scalarNode('path')->cannotBeEmpty()->defaultValue('/solr/default')->end()
            ->end()
        ;


        return $treeBuilder;
    }
}

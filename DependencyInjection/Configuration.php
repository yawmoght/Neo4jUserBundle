<?php

namespace Frne\Bundle\Neo4jUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('frne_neo4j_user');

        $rootNode
            ->children()
                ->scalarNode('entity_classname')
                    ->info('A fully qualified classname to use for user entities')
                    ->defaultValue('Frne\Bundle\Neo4jUserBundle\Entity\User')
                ->end()
            ->end();

        return $treeBuilder;
    }
}

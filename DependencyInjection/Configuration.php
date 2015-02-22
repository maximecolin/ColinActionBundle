<?php

namespace Colin\Bundle\ActionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('colin_action');

        $rootNode
            ->children()
                ->variableNode('configurations')
                    ->treatNullLike([])
                    ->defaultValue([])
                ->end()
                ->arrayNode('actions')
                    ->prototype('array') // group
                        ->prototype('array') // controller
                            ->prototype('array') // action
                                ->children()
                                    ->scalarNode('type') // type
                                    ->end()
                                    ->arrayNode('configs') // config
                                        ->prototype('variable')
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

<?php

namespace Colin\Bundle\ActionBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class ReadActionConfiguration extends Configuration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('configs');

        $rootNode
            ->children()
                ->scalarNode('path')
                    ->defaultValue(sprintf('/%s/%s/{id}/read', $this->groupName, $this->controllerName, $this->actionName))
                ->end()
                ->scalarNode('host')
                    ->defaultValue('')
                ->end()
                ->scalarNode('security')
                    ->defaultNull()
                ->end()
                ->scalarNode('entity_class')
                ->end()
                ->scalarNode('template')
                ->end()
            ->end();

        return $treeBuilder;
    }

    public function getDefinition()
    {
        $definition = new DefinitionDecorator('colin_action.action.read');
        $definition->replaceArgument(2, $this->configs);

        return $definition;
    }
}

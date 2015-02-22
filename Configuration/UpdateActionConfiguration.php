<?php

namespace Colin\Bundle\ActionBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class UpdateActionConfiguration extends Configuration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('configs');

        $rootNode
            ->children()
                ->scalarNode('path')
                    ->defaultValue(sprintf('/%s/%s/{id}/%s', $this->groupName, $this->controllerName, $this->actionName))
                ->end()
                ->scalarNode('host')
                    ->defaultValue('')
                ->end()
                ->scalarNode('security')
                    ->defaultNull()
                ->end()
                ->scalarNode('entity_class')
                ->end()
                ->scalarNode('form_type')
                ->end()
                ->scalarNode('template')
                ->end()
            ->end();

        return $treeBuilder;
    }

    public function getDefinition()
    {
        $definition = new DefinitionDecorator('colin_action.action.update');
        $definition->replaceArgument(4, $this->configs);

        return $definition;
    }
}

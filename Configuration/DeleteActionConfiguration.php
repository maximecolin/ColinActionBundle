<?php

namespace Colin\Bundle\ActionBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class DeleteActionConfiguration extends Configuration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('configs');

        return $treeBuilder;
    }

    public function getDefinition()
    {
        $definition = new DefinitionDecorator('colin_action.action.delete');
        $definition->replaceArgument(4, $this->configs);

        return $definition;
    }
}

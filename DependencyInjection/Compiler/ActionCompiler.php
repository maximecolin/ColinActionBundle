<?php

namespace Colin\Bundle\ActionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ActionCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $routeLoaderDefinition = $container->getDefinition('colin_action.routing.action_loader');

        $configurations = $container->getParameter('colin_actions.configurations');
        $groups         = $container->getParameter('colin_actions.actions');

        foreach ($groups as $groupName => $group) {

            foreach ($group as $controllerName => $controller) {

                foreach ($controller as $actionName => $action) {

                    $class = $configurations[$action['type']];

                    $configuration = new $class($groupName, $controllerName, $actionName, ['configs' => $action['configs']]);

                    if (!$configuration instanceof \Colin\Bundle\ActionBundle\Configuration\Configuration) {
                        throw \Exception();
                    }

                    // Create service
                    $container->setDefinition($configuration->getId(), $configuration->getDefinition());

                    // Register route
                    $routeLoaderDefinition->addMethodCall('addRoute', [
                        $configuration->getRouteName(),
                        $configuration->getRoute()
                    ]);

                }

            }

        }
    }
}
# ColinActionBundle

This bundle provides configuration based engine to declare generic actions with routes. The bundle come with 5 generic CRUD actions (Create, Read, Update, Delete, List) which can be configured for all your entity.

You can also add your own generic actions or extends existant ones.

## Configuration

```
colin_action:
    configurations:
        list:   Colin\Bundle\ActionBundle\Configuration\ListActionConfiguration
        create: Colin\Bundle\ActionBundle\Configuration\CreateActionConfiguration
        update: Colin\Bundle\ActionBundle\Configuration\UpdateActionConfiguration
        delete: Colin\Bundle\ActionBundle\Configuration\DeleteActionConfiguration
        read:   Colin\Bundle\ActionBundle\Configuration\ReadActionConfiguration
    actions:
        admin:
            dummy:
                create:
                    type: create
                    configs:
                        #path:         /dummy/create
                        entity_class: Acme\DemoBundle\Entity\Dummy
                        form_type:    Acme\DemoBundle\Form\Type\DummyType
                        template:     AcmeDemoBundle:Dummy:create.html.twig
                        host:         photo.%domain%
                update:
                    type: update
                    configs:
                        #path: ~
                        entity_class: Acme\DemoBundle\Entity\Dummy
                        form_type:    Acme\DemoBundle\Form\Type\DummyType
                        template:     AcmeDemoBundle:Dummy:create.html.twig
                        host:         photo.%domain%
                read:
                    type: read
                    configs:
                        #path: ~
                        entity_class: Acme\DemoBundle\Entity\Dummy
                        template:     AcmeDemoBundle:Dummy:read.html.twig
                        host:         photo.%domain%
                list:
                    type: list
                    configs:
                        #path: ~
                        entity_class: Acme\DemoBundle\Entity\Dummy
                        template:     AcmeDemoBundle:Dummy:list.html.twig
                        host:         photo.%domain%
```

This config will generate 5 actions and routes to administrate the Dummy entity.

## Create your own generic action

### Create your action as service

```
<?php

namespace Demo\AcmeBundle\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MyAction
{
    public function execute(Request $request)
    {
        return new Response();
    }
}
```


```
services:
    acme_demo.action.my:
        class: Demo\AcmeBundle\Action\MyAction
        abstract: true
        arguments:
```

### Create your action configuration

```
<?php

namespace Acme\DemoBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class MyActionConfiguration extends Configuration
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('configs');

        $rootNode
            ->children()
				// Put your configuration here
            ->end();

        return $treeBuilder;
    }

    public function getDefinition()
    {
        $definition = new DefinitionDecorator('colin_action.action.create');
        $definition->replaceArgument(4, $this->configs);

        return $definition;
    }
}
```

In config.yml

```
colin_action:
    configurations:
        # ...
        my_action: Acme\DemoBundle\Configuration\MyActionConfiguration
```

### Utilisez votre nouvelle action

In config.yml

```
colin_action:
    actions:
        # ...
        admin:
            dummy:
                foobar:
                    type: my_action
                    configs:
                        # ...
```
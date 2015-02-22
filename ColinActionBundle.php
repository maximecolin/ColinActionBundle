<?php

namespace Colin\Bundle\ActionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Colin\Bundle\ActionBundle\DependencyInjection\Compiler\ActionCompiler;

class ColinActionBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ActionCompiler());
    }
}

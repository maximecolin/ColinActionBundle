<?php

namespace Colin\Bundle\ActionBundle\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

abstract class Configuration implements ConfigurationInterface
{
    protected $groupName;

    protected $controllerName;

    protected $actionName;

    protected $configs;

    public function __construct($groupName, $controllerName, $actionName, array $configs)
    {
        $this->groupName      = $groupName;
        $this->controllerName = $controllerName;
        $this->actionName     = $actionName;
        $this->configs        = (new Processor())->processConfiguration($this, $configs);
    }

    public function getId()
    {
        return sprintf('%s.%s.%s', $this->groupName, $this->controllerName, $this->actionName);
    }

    public function getRouteName()
    {
        return sprintf('%s_%s_%s', $this->groupName, $this->controllerName, $this->actionName);
    }

    abstract function getDefinition();

    public function getRoute()
    {
        $path         = $this->configs['path'];
        $defaults     = ['_controller' => sprintf('%s:execute', $this->getId())];
        $requirements = [];
        $options      = [];
        $host         = $this->configs['host'];
        $schemes      = [];
        $methods      = [];
        $condition    = '';

        if ($this->configs['security']) {
            $defaults['_security'] = $this->configs['security'];
        }

        return [
            'path'         => $path,
            'defaults'     => $defaults,
            'requirements' => $requirements,
            'options'      => $options,
            'host'         => $host,
            'schemes'      => $schemes,
            'methods'      => $methods,
            'condition'    => $condition,
        ];
    }
}

<?php

namespace Colin\Bundle\ActionBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ActionLoader implements LoaderInterface
{
    const TYPE = 'action';

    private $loaded = false;

    private $routes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function addRoute($name, array $route)
    {
        if (isset($route['defaults']['_security'])) {
            $route['defaults']['_security'] = new Security(['expression' => $route['defaults']['_security']]);
        }

        $this->routes->add($name, new Route(
            $route['path'],
            $route['defaults'],
            $route['requirements'],
            $route['options'],
            $route['host'],
            $route['schemes'],
            $route['methods'],
            $route['condition']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {
            throw new \RuntimeException('Admin loader already loaded');
        }

        $this->loaded = true;

        return $this->routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return static::TYPE === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }
}

<?php

namespace Colin\Bundle\ActionBundle\Action;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class ListAction implements ActionInterface
{
    private $formFactory;

    private $templating;

    private $router;

    private $entityManager;

    private $configs;

    public function __construct(
        FormFactoryInterface $formFactory,
        EngineInterface $templating,
        RouterInterface $router,
        EntityManager $entityManager,
        array $configs
    ) {
        $this->formFactory   = $formFactory;
        $this->templating    = $templating;
        $this->router        = $router;
        $this->entityManager = $entityManager;
        $this->configs       = $configs;
    }

    public function execute(Request $request)
    {
        $repository = $this->entityManager->getRepository($this->configs['entity_class']);
        $method     = 'findBy';

        $entities = $repository->$method([]);

        return new Response($this->templating->render($this->configs['template'], [
            'entities' => $entities,
        ]));
    }
}

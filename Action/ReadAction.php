<?php

namespace Colin\Bundle\ActionBundle\Action;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class ReadAction implements ActionInterface
{
    private $templating;

    private $entityManager;

    private $configs;

    public function __construct(
        EngineInterface $templating,
        EntityManager $entityManager,
        array $configs
    ) {
        $this->templating    = $templating;
        $this->entityManager = $entityManager;
        $this->configs       = $configs;
    }

    public function execute(Request $request)
    {
        $repository = $this->entityManager->getRepository($this->configs['entity_class']);
        $method     = 'find';

        $entity = $repository->$method($request->get('id'));

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found.');
        }

        return new Response($this->templating->render($this->configs['template'], [
            'entity' => $entity,
        ]));
    }
}

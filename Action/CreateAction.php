<?php

namespace Colin\Bundle\ActionBundle\Action;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;

class CreateAction
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
        $entity = new $this->configs['entity_class']();

        $form = $this->formFactory->create(new $this->configs['form_type'](), $entity, [
            'action' => $request->getUri(),
            'method' => 'POST',
        ]);
        $form->add('submit', 'submit');

        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $request->getSession()->getFlashBag()->add('success', 'Create success');

                return new RedirectResponse($request->getUri());
            } else {
                $request->getSession()->getFlashBag()->add('error', 'Create error');
            }
        }

        return new Response($this->templating->render($this->configs['template'], [
            'form'   => $form->createView(),
            'entity' => $entity,
        ]));
    }
}

<?php

namespace Colin\Bundle\ActionBundle\Action;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;

class UpdateAction implements ActionInterface
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
        $method     = 'find';

        $entity = $repository->$method($request->get('id'));

        if (!$entity) {
            throw new NotFoundHttpException('Entity not found.');
        }

        $form = $this->formFactory->create(new $this->configs['form_type'](), $entity, [
            'action' => $request->getUri(),
            'method' => 'POST',
        ]);
        $form->add('submit', 'submit');

        if ($form->handleRequest($request)->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->persist($entity);
                $this->entityManager->flush();

                $request->getSession()->getFlashBag()->add('success', 'Update success');

                return new RedirectResponse($request->getUri());
            } else {
                $request->getSession()->getFlashBag()->add('error', 'Delete error');
            }
        }

        return new Response($this->templating->render($this->configs['template'], [
            'form'   => $form->createView(),
            'entity' => $entity,
        ]));
    }
}

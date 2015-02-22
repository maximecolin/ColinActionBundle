<?php

namespace Colin\Bundle\ActionBundle\Action;

use Symfony\Component\HttpFoundation\Request;

interface ActionInterface
{
    public function execute(Request $request);
}

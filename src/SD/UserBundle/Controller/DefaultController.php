<?php

namespace SD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SDUserBundle:Default:index.html.twig');
    }
}

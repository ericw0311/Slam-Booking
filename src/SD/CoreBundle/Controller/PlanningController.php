<?php
// src/SD/CoreBundle/Controller/PlanningController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\FileContext;

class PlanningController extends Controller
{
    public function indexAction()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('SDCoreBundle:Planning:index.html.twig', array('userContext' => $userContext));
    }

	/**
	 * @Route("/planning/foo/{id}/bar", options={"expose"=true}, name="my_route_to_expose")
	*/
	public function fooAction() {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('SDCoreBundle:Planning:FOSJsRouting1.html.twig', array('userContext' => $userContext));
	}

	/**
	 * @Route("/blog/{page}",
	 *     defaults = { "page" = 1 },
	 *     options = { "expose" = true },
	 *     name = "my_route_to_expose_with_defaults",
	 * )
	*/
	public function blogAction($page) {
	return $this->render('SDCoreBundle:Planning:FOSJsRouting2.html.twig');
	}
}

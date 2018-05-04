<?php
// src/SD/CoreBundle/Controller/DefaultController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\FileContext;

class DefaultController extends Controller
{
    public function indexAction()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    if ($userContext->getCurrentFileID() <= 0) {
        return $this->render('SDCoreBundle:Default:index.html.twig', array('userContext' => $userContext));
    } else {
		return $this->redirectToRoute('sd_core_summary');
    }
    }

    public function summaryAction()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();

    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $fileContext = new FileContext($em, $userContext->getCurrentFile(), $userContext->getCurrentUserFile()); // contexte dossier

    return $this->render('SDCoreBundle:Default:summary.html.twig', array('userContext' => $userContext, 'fileContext' => $fileContext));
    }
}

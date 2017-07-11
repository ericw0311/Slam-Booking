<?php
// src/SD/CoreBundle/Controller/ResourceClassificationController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\Constants;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ResourceClassificationController extends Controller
{
	// Affichage des classifications du dossier en cours
	public function indexAction($resourceType, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $activeResourceClassifications = Constants::RESOURCE_CLASSIFICATION_ACTIVE;

    return $this->render('SDCoreBundle:ResourceClassification:index.html.twig',
		array('userContext' => $userContext,
			'resourceType' => $resourceType,
			'activeResourceClassifications' => $activeResourceClassifications));
    }
}

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
use SD\CoreBundle\Entity\ResourceClassification;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ResourceClassificationController extends Controller
{
	// Affichage des classifications du dossier en cours
	public function indexAction($resourceType, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $defaultActiveRC = Constants::RESOURCE_CLASSIFICATION_ACTIVE[$resourceType]; // Classifications actives par défaut

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

    $activeRC = $RCRepository->getResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 1); // Classifications actives
    $unactiveRC = $RCRepository->getResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 0); // Classifications inactives


	// Les classifications actives sont celles qui sont actives par defaut ou actives en base et qui ne sont pas inactives en base
    $activeResourceClassifications = array();

    foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassification) {
		if ((in_array($resourceClassification, $defaultActiveRC) || in_array($resourceClassification, $activeRC))
			&& !in_array($resourceClassification, $unactiveRC))
		{
			array_push($activeResourceClassifications, $resourceClassification);
		}
	}

    return $this->render('SDCoreBundle:ResourceClassification:index.html.twig',
		array('userContext' => $userContext,
			'resourceType' => $resourceType,
			'activeResourceClassifications' => $activeResourceClassifications));
    }

	// Activation d'une classification interne
	public function activateinternalAction($resourceType, $classificationCode, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
    $resourceClassification = $RCRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $classificationCode));

    if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($classificationCode);
        $resourceClassification->setName($classificationCode);
        $em->persist($resourceClassification);
        $resourceClassification->setActive(1);
	} else {
		$resourceClassification->setActive(1);
	}
	$em->flush();

    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.activated.ok');
    return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }

	// Désactivation d'une classification interne
	public function unactivateinternalAction($resourceType, $classificationCode, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceClassificationRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
    $resourceClassification = $resourceClassificationRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $classificationCode));

    if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($classificationCode);
        $resourceClassification->setName($classificationCode);
        $em->persist($resourceClassification);
        $resourceClassification->setActive(0);
	} else {
		$resourceClassification->setActive(0);
	}
	$em->flush();

    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.unactivated.ok');
    return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }
}

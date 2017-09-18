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
use SD\CoreBundle\Form\ResourceClassificationType;

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

    $activeInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 1); // Classifications internes actives (lues en BD)
    $unactiveInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 0); // Classifications internes inactives (lues en BD)

    $listExternalRC = $RCRepository->getExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);
                
	// Les classifications actives sont celles qui sont actives par defaut ou actives en base et qui ne sont pas inactives en base
    $activeInternalRC = array();

    foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassification) {
		if ((in_array($resourceClassification, $defaultActiveRC) || in_array($resourceClassification, $activeInternalRC_DB))
			&& !in_array($resourceClassification, $unactiveInternalRC_DB))
		{
			array_push($activeInternalRC, $resourceClassification);
		}
	}

    return $this->render('SDCoreBundle:ResourceClassification:index.html.twig',
		array('userContext' => $userContext,
			'resourceType' => $resourceType,
			'activeInternalRC' => $activeInternalRC,
			'listExternalRC' => $listExternalRC));
    }

	// Activation d'une classification interne
	public function activate_internalAction($resourceType, $classificationCode, Request $request)
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
	public function unactivate_internalAction($resourceType, $classificationCode, Request $request)
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
    

    // Activation d'une classification externe
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function activate_externalAction($resourceType, ResourceClassification $resourceClassification, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification->setActive(1);
	$em->flush();

    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.activated.ok');
    return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }


    // Desactivation d'une classification externe
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function unactivate_externalAction($resourceType, ResourceClassification $resourceClassification, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification->setActive(0);
	$em->flush();

    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.unactivated.ok');
    return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }



	// Ajout d'une classification de ressource
    public function addAction($resourceType, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());

	$resourceClassification->setInternal(0);
	$resourceClassification->setType($resourceType);
	$resourceClassification->setActive(1);

	$form = $this->createForm(ResourceClassificationType::class, $resourceClassification);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

		$em->persist($resourceClassification);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'resourceClassification.created.ok');

		return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
	}

	$request->getSession()->getFlashBag()->add('notice', 'resourceClassification.create.warning');

    return $this->render('SDCoreBundle:ResourceClassification:add.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'form' => $form->createView()));
    }

	
    // Modification d'une classification de ressource
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function modifyAction($resourceType, ResourceClassification $resourceClassification, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$form = $this->createForm(ResourceClassificationType::class, $resourceClassification);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la classification de ressource
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.updated.ok');

		return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }

    return $this->render('SDCoreBundle:ResourceClassification:modify.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'form' => $form->createView()));
    }


    // Suppression d'une classification de ressource
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function deleteAction($resourceType, ResourceClassification $resourceClassification, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la classification de ressource
        $em->remove($resourceClassification);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.deleted.ok');

		return $this->redirectToRoute('sd_core_resourceclassification_display', array('resourceType' => $resourceType));
    }

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

    if ($resourceRepository->getResourcesCount_RC($resourceClassification) > 0) {
		return $this->redirectToRoute('sd_core_resourceclassification_foreign',
			array('resourceType' => $resourceType, 'resourceClassificationID' => $resourceClassification->getId()));
	}

    return $this->render('SDCoreBundle:ResourceClassification:delete.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'form' => $form->createView()));
    }


    // Affichage des ressources d'une classification
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function foreignAction($resourceType, ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

    $listResources = $resourceRepository->getResources_RC($resourceClassification);
                
    return $this->render('SDCoreBundle:ResourceClassification:foreign.html.twig', array(
                'userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification,
		'listResources' => $listResources));
    }
}

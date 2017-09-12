<?php
// src/SD/CoreBundle/Controller/ResourceController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\Resource;
use SD\CoreBundle\Entity\ResourceClassification;
use SD\CoreBundle\Form\ResourceType;
use SD\CoreBundle\Form\ResourceAddType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\Constants;
use SD\CoreBundle\Entity\ResourceClassificationNDB;
use SD\CoreBundle\Entity\ResourceContext;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ResourceController extends Controller
{
	// Affichage des ressources du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

    $numberRecords = $resourceRepository->getResourcesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'resource', $pageNumber, $numberRecords, 'sd_core_resource_list', 'sd_core_resource_classification');

    $listResources = $resourceRepository->getDisplayedResources($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Resource:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listResources' => $listResources));
    }

	// Ajout d'une ressource: selection de la classification
    public function classificationAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
	$activeRC = array();

    foreach (Constants::RESOURCE_TYPE as $resourceType) {

		$defaultActiveRC = Constants::RESOURCE_CLASSIFICATION_ACTIVE[$resourceType]; // Classifications actives par défaut

		$activeInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 1); // Classifications internes actives (lues en BD)
		$unactiveInternalRC_DB = $RCRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 0); // Classifications internes inactives (lues en BD)

		foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassificationCode) {
			if ((in_array($resourceClassificationCode, $defaultActiveRC) || in_array($resourceClassificationCode, $activeInternalRC_DB))
				&& !in_array($resourceClassificationCode, $unactiveInternalRC_DB))
			{
			$resourceClassificationNDB = new ResourceClassificationNDB();
			$resourceClassificationNDB->setInternal(1);
			$resourceClassificationNDB->setType($resourceType);
			$resourceClassificationNDB->setCode($resourceClassificationCode);
			array_push($activeRC, $resourceClassificationNDB);
			}
		}

		$activeExternalRC = $RCRepository->getActiveExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);
		foreach ($activeExternalRC as $resourceClassification) {
			$resourceClassificationNDB = new ResourceClassificationNDB();
			$resourceClassificationNDB->setInternal(0);
			$resourceClassificationNDB->setType($resourceType);
			$resourceClassificationNDB->setId($resourceClassification->getId());
			$resourceClassificationNDB->setName($resourceClassification->getName());
			array_push($activeRC, $resourceClassificationNDB);
		}
	}

    return $this->render('SDCoreBundle:Resource:classification.html.twig',
		array('userContext' => $userContext, 'activeRC' => $activeRC));
    }


	// Ajout d'une ressource de classification interne
    public function add_internalAction($type, $code, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resource = new Resource($connectedUser, $userContext->getCurrentFile());
	$resource->setInternal(true);
	$resource->setType($type);
	$resource->setCode($code);
	$resource->setBackgroundColor("#0000ff");
	$resource->setForegroundColor("#ffffff");

	$form = $this->createForm(ResourceAddType::class, $resource);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($resource);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'resource.created.ok');


		if ($form->get('validateAndCreate')->isClicked()) {
			return $this->redirectToRoute('sd_core_resource_addinternal', array('type' => $type, 'code' => $code));
		} else {
			return $this->redirectToRoute('sd_core_resource_list', array('pageNumber' => 1));
		}

	}
    return $this->render('SDCoreBundle:Resource:add.html.twig', array('userContext' => $userContext, 'resourceClassification' => null, 'resource' => $resource, 'form' => $form->createView()));
    }


    // Ajout d'une ressource de classification externe
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function add_externalAction($type, ResourceClassification $resourceClassification, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resource = new Resource($connectedUser, $userContext->getCurrentFile());
	$resource->setInternal(false);
	$resource->setType($type);
	$resource->setClassification($resourceClassification);
	$resource->setBackgroundColor("#0000ff");
	$resource->setForegroundColor("#ffffff");

	$form = $this->createForm(ResourceAddType::class, $resource);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($resource);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'resource.created.ok');

		if ($form->get('validateAndCreate')->isClicked()) {
			return $this->redirectToRoute('sd_core_resource_addexternal', array('type' => $type, 'resourceClassificationID' => $resourceClassification->getId()));
		} else {
			return $this->redirectToRoute('sd_core_resource_list', array('pageNumber' => 1));
		}
	}
    return $this->render('SDCoreBundle:Resource:add.html.twig', array('userContext' => $userContext, 'resourceClassification' => $resourceClassification, 'resource' => $resource, 'form' => $form->createView()));
    }

	
    // Edition du detail d'une ressource
    /**
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
    */
    public function editAction(Resource $resource)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceContext = new ResourceContext($em, $resource); // contexte ressource

    return $this->render('SDCoreBundle:Resource:edit.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'resourceContext' => $resourceContext));
    }

	
    // Modification d'une ressource
    /**
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
    */
    public function modifyAction(Resource $resource, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(ResourceType::class, $resource);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la ressource
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resource.updated.ok');

        return $this->redirectToRoute('sd_core_resource_edit', array('resourceID' => $resource->getId()));
    }
    return $this->render('SDCoreBundle:Resource:modify.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }


    // Suppression d'une ressource
    /**
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
    */
    public function deleteAction(Resource $resource, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la ressource
        $em->remove($resource);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resource.deleted.ok');

        return $this->redirectToRoute('sd_core_resource_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Resource:delete.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }


    // Affichage des periodes de planification d'une ressource
    /**
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
    */
    public function foreignAction(Resource $resource, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationPeriodRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');

    $listPlanificationPeriod = $planificationPeriodRepository->getResourcePlanificationPeriods($resource);
                
    return $this->render('SDCoreBundle:Resource:foreign.html.twig', array(
                'userContext' => $userContext, 'resource' => $resource,
		'listPlanificationPeriod' => $listPlanificationPeriod));
    }
}

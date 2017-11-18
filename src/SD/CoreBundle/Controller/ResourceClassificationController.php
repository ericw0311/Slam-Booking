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
use SD\CoreBundle\Api\ResourceApi;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ResourceClassificationController extends Controller
{
	// Affichage des classifications du dossier en cours
	public function indexAction($resourceType, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	// Classifications internes actives
    $activeInternalRC = ResourceApi::getActiveInternalResourceClassifications($em, $userContext->getCurrentFile(), $resourceType);

	// Nombre de ressources par classification interne
	$numberResourcesInternalRC =  ResourceApi::getInternalClassificationNumberResources($em, $userContext->getCurrentFile(), $resourceType);

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');

	// Classifications externes
    $listExternalRC = $RCRepository->getExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

	// Nombre de ressources par classification externe
	$numberResourcesExternalRC =  ResourceApi::getExternalClassificationNumberResources($em, $userContext->getCurrentFile(), $resourceType, $listExternalRC);

    return $this->render('SDCoreBundle:ResourceClassification:index.html.twig',
		array('userContext' => $userContext,
			'resourceType' => $resourceType,
			'activeInternalRC' => $activeInternalRC,
			'numberResourcesInternalRC' => $numberResourcesInternalRC,
			'listExternalRC' => $listExternalRC,
			'numberResourcesExternalRC' => $numberResourcesExternalRC));
    }


	// Activation d'une classification interne
	public function activate_internalAction($resourceType, $resourceClassificationCode, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $RCRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
    $resourceClassification = $RCRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $resourceClassificationCode));

    if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($resourceClassificationCode);
        $resourceClassification->setName($resourceClassificationCode);
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
	public function unactivate_internalAction($resourceType, $resourceClassificationCode, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceClassificationRepository = $em->getRepository('SDCoreBundle:ResourceClassification');
    $resourceClassification = $resourceClassificationRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $resourceClassificationCode));

    if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($resourceClassificationCode);
        $resourceClassification->setName($resourceClassificationCode);
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

    return $this->render('SDCoreBundle:ResourceClassification:delete.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'form' => $form->createView()));
    }


    // Affichage des ressources d'une classification interne
    public function foreign_internalAction($resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	if ($resourceType == 'USER') {
		$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
		$listUserFiles = $userFileRepository->getUserFilesFrom_IRC($userContext->getCurrentFile(), $resourceClassificationCode);

		return $this->render('SDCoreBundle:ResourceClassification:foreign.user.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'action' => 'unactivate', 'listUserFiles' => $listUserFiles));
	} else {
		$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
		$listResources = $resourceRepository->getResources_IRC($userContext->getCurrentFile(), $resourceType, $resourceClassificationCode);
                
		return $this->render('SDCoreBundle:ResourceClassification:foreign.internal.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'listResources' => $listResources));
	}
    }


    // Affichage des ressources d'une classification externe
    /**
    * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
    */
    public function foreign_externalAction($resourceType, ResourceClassification $resourceClassification, $action)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	if ($resourceType == 'USER') {
		$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
		$listUserFiles = $userFileRepository->getUserFilesFrom_ERC($userContext->getCurrentFile(), $resourceClassification);

		return $this->render('SDCoreBundle:ResourceClassification:foreign.user.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'action' => $action, 'listUserFiles' => $listUserFiles));
	} else {
		$resourceRepository = $em->getRepository('SDCoreBundle:Resource');
		$listResources = $resourceRepository->getResources_ERC($userContext->getCurrentFile(), $resourceType, $resourceClassification);
                
		return $this->render('SDCoreBundle:ResourceClassification:foreign.external.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'action' => $action, 'listResources' => $listResources));
	}
    }
}

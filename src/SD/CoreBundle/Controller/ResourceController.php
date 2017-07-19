<?php
// src/SD/CoreBundle/Controller/ResourceController.php

namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\Resource;
use SD\CoreBundle\Form\ResourceType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ResourceController extends Controller
{
	// Affichage des activites du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

    $numberRecords = $resourceRepository->getResourcesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'core', 'resource', $pageNumber, $numberRecords, 'sd_core_resource_list', 'sd_core_resource_add');

    $listResources = $resourceRepository->getDisplayedResources($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Resource:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listResources' => $listResources));
    }

	// Ajout d'une activite
    public function addAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resource = new Resource($connectedUser, $userContext->getCurrentFile());

	$form = $this->createForm(ResourceType::class, $resource);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($resource);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'resource.created.ok');

		return $this->redirectToRoute('sd_core_resource_list', array('pageNumber' => 1));
	}
    return $this->render('SDCoreBundle:Resource:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }

	
    // Edition du detail d'une activite
    /**
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
    */
    public function editAction(Resource $resource)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Resource:edit.html.twig', array('userContext' => $userContext, 'resource' => $resource));
    }

	
    // Modification d'une activite
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
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resource.updated.ok');

        return $this->redirectToRoute('sd_core_resource_edit', array('resourceID' => $resource->getId()));
    }
    return $this->render('SDCoreBundle:Resource:modify.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }


    // Suppression d'une activite
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
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->remove($resource);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'resource.deleted.ok');

        return $this->redirectToRoute('sd_core_resource_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Resource:delete.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }
}

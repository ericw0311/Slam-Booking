<?php
// src/SD/CoreBundle/Controller/PlanificationController.php
namespace SD\CoreBundle\Controller;
use SD\CoreBundle\Entity\PlanificationHeader;
use SD\CoreBundle\Form\PlanificationHeaderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\ResourceNDBPlanification;
use SD\CoreBundle\Entity\Trace;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PlanificationController extends Controller
{
	// Affichage des planifications du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $planificationHeaderRepository = $em->getRepository('SDCoreBundle:PlanificationHeader');
    $numberRecords = $planificationHeaderRepository->getPlanificationHeadersCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'core', 'planification', $pageNumber, $numberRecords, 'sd_core_planification_list', 'sd_core_planification_type');
    $listPlanificationHeaders = $planificationHeaderRepository->getDisplayedPlanificationHeaders($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Planification:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listPlanificationHeaders' => $listPlanificationHeaders));
    }

	// Ajout d'une planification: Sélection du type de ressources à planifier
    public function typeAction(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Planification:type.html.twig', array('userContext' => $userContext));
    }
	
	// Ajout d'une planification: sélection des ressources à planifier
    public function addAction($type, $resourceIDList, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceIDArray = explode('-', $resourceIDList);
	
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

	$selectedResources = array();
	$i = 0;

    foreach ($resourceIDArray as $resourceID) {
		$resourceDB = $resourceRepository->find($resourceID);
		if ($resourceDB !== null) {
			$resource = new ResourceNDBPlanification();
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());

			$resourceIDArray_tprr = $resourceIDArray;
			unset($resourceIDArray_tprr[$i++]);
			$resource->setResourceIDList(implode('-', $resourceIDArray_tprr));

			array_push($selectedResources, $resource);
		}
	}

    $resourcesToPlanifyDB = $resourceRepository->getResourcesToPlanify($userContext->getCurrentFile(), $type);
                
	$resourcesToPlanify = array();
    foreach ($resourcesToPlanifyDB as $resourceDB) {
		if (array_search($resourceDB->getId(), $resourceIDArray) === false) {
			$resource = new ResourceNDBPlanification();
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());
			$resource->setResourceIDList(($resourceIDList == '') ? $resourceDB->getId() : ($resourceIDList.'-'.$resourceDB->getId()));
			array_push($resourcesToPlanify, $resource);
		}
	}

    return $this->render('SDCoreBundle:Planification:add.html.twig', array('userContext' => $userContext, 'type' => $type, 'selectedResources' => $selectedResources, 'resourcesToPlanify' => $resourcesToPlanify));
    }
	
	// Ajout d'une planification
    public function addAction_sav(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$planificationHeader = new PlanificationHeader($connectedUser, $userContext->getCurrentFile());
	$form = $this->createForm(PlanificationHeaderType::class, $planificationHeader);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$em->persist($planificationHeader);
		$em->flush();
		$request->getSession()->getFlashBag()->add('notice', 'planification.created.ok');
		return $this->redirectToRoute('sd_core_planification_list', array('pageNumber' => 1));
	}
    return $this->render('SDCoreBundle:Planification:add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }
	
    // Edition du detail d'une planification
    /**
    * @ParamConverter("planificationHeader", options={"mapping": {"planificationHeaderID": "id"}})
    */
    public function editAction(PlanificationHeader $planificationHeader)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    return $this->render('SDCoreBundle:Planification:edit.html.twig', array('userContext' => $userContext, 'planificationHeader' => $planificationHeader));
    }
	
    // Modification d'une planification
    /**
    * @ParamConverter("planificationHeader", options={"mapping": {"planificationHeaderID": "id"}})
    */
    public function modifyAction(PlanificationHeader $planificationHeader, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->createForm(PlanificationHeaderType::class, $planificationHeader);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.updated.ok');
        return $this->redirectToRoute('sd_core_planification_edit', array('planificationHeaderID' => $planificationHeader->getId()));
    }
    return $this->render('SDCoreBundle:Planification:modify.html.twig', array('userContext' => $userContext, 'planificationHeader' => $planificationHeader, 'form' => $form->createView()));
    }
    // Suppression d'une planification
    /**
    * @ParamConverter("planificationHeader", options={"mapping": {"planificationHeaderID": "id"}})
    */
    public function deleteAction(PlanificationHeader $planificationHeader, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->get('form.factory')->create();
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->remove($planificationHeader);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.deleted.ok');
        return $this->redirectToRoute('sd_core_planification_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Planification:delete.html.twig', array('userContext' => $userContext, 'planificationHeader' => $planificationHeader, 'form' => $form->createView()));
    }
}

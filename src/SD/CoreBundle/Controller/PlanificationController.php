<?php
// src/SD/CoreBundle/Controller/PlanificationController.php
namespace SD\CoreBundle\Controller;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\PlanificationResource;
use SD\CoreBundle\Form\PlanificationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;
use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\ResourceNDBPlanificationAdd;
use SD\CoreBundle\Entity\ResourceNDBPlanificationSelected;
use SD\CoreBundle\Entity\Trace;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PlanificationController extends Controller
{
    // Retourne un tableau des ressources selectionnées
    static function getSelectedResources($em, $resourceIDList)
    {
	$resourceIDArray = explode('-', $resourceIDList);
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');

	$selectedResources = array();
	$i = 0;

    foreach ($resourceIDArray as $resourceID) {
		$resourceDB = $resourceRepository->find($resourceID);
		if ($resourceDB !== null) {
			$resource = new ResourceNDBPlanificationSelected();
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());

			$resourceIDArray_tprr = $resourceIDArray;
			unset($resourceIDArray_tprr[$i]);
			$resource->setResourceIDList_unselect(implode('-', $resourceIDArray_tprr));

			if (count($resourceIDArray) > 1) {
				if ($i > 0) {
					$resourceIDArray_tprr = $resourceIDArray;
					$resourceIDArray_tprr[$i] = $resourceIDArray_tprr[$i-1];
					$resourceIDArray_tprr[$i-1] = $resourceID;
					$resource->setResourceIDList_sortBefore(implode('-', $resourceIDArray_tprr));
				}
				if ($i < count($resourceIDArray)-1) {
					$resourceIDArray_tprr = $resourceIDArray;
					$resourceIDArray_tprr[$i] = $resourceIDArray_tprr[$i+1];
					$resourceIDArray_tprr[$i+1] = $resourceID;
					$resource->setResourceIDList_sortAfter(implode('-', $resourceIDArray_tprr));
				}
			}
			$i++;
			array_push($selectedResources, $resource);
		}
	}
	return $selectedResources;
    }


    // Retourne un tableau des ressources à planifier
    static function getResourcesToPlanify($em, \SD\CoreBundle\Entity\File $file, $type, $resourceIDList)
    {
	$resourceIDArray = explode('-', $resourceIDList);
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $resourcesToPlanifyDB = $resourceRepository->getResourcesToPlanify($file, $type);
                
	$resourcesToPlanify = array();
    foreach ($resourcesToPlanifyDB as $resourceDB) {
		if (array_search($resourceDB->getId(), $resourceIDArray) === false) {
			$resource = new ResourceNDBPlanificationAdd();
			$resource->setId($resourceDB->getId());
			$resource->setName($resourceDB->getName());
			$resource->setInternal($resourceDB->getInternal());
			$resource->setType($resourceDB->getType());
			$resource->setCode($resourceDB->getCode());
			$resource->setResourceIDList(($resourceIDList == '') ? $resourceDB->getId() : ($resourceIDList.'-'.$resourceDB->getId()));
			array_push($resourcesToPlanify, $resource);
		}
	}

	return $resourcesToPlanify;
    }


	// Affichage des planifications du dossier en cours
	public function indexAction($pageNumber)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');
    $numberRecords = $planificationRepository->getPlanificationsCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'core', 'planification', $pageNumber, $numberRecords, 'sd_core_planification_list', 'sd_core_planification_type');
    $listPlanifications = $planificationRepository->getDisplayedPlanifications($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
    return $this->render('SDCoreBundle:Planification:index.html.twig', array(
                'userContext' => $userContext,
                'listContext' => $listContext,
		'listPlanifications' => $listPlanifications));
    }

	// Ajout d'une planification: Sélection du type de ressources à planifier
    public function typeAction()
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Planification:type.html.twig', array('userContext' => $userContext));
    }
	

	// Initialisation des ressources à planifier
    public function initresourceAction($type, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = PlanificationController::getSelectedResources($em, $resourceIDList);

	$resourcesToPlanify = PlanificationController::getResourcesToPlanify($em, $userContext->getCurrentFile(), $type, $resourceIDList);

    return $this->render('SDCoreBundle:Planification:resource.insert.html.twig', 
		array('userContext' => $userContext, 'type' => $type, 'selectedResources' => $selectedResources, 
		'selectedResourcesIDList' => $resourceIDList, 'resourcesToPlanify' => $resourcesToPlanify));
    }


	// Validation des ressources à planifier
    public function validateinitresourceAction($type, $resourceIDList, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceIDArray = explode('-', $resourceIDList);
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$i = 0;

	$planification = new Planification($connectedUser, $userContext->getCurrentFile());
	$planification->setType($type);

	$planificationPeriod = new PlanificationPeriod($connectedUser, $planification);

    foreach ($resourceIDArray as $resourceID) {
		$resourceDB = $resourceRepository->find($resourceID);
		if ($i++ == 0) {
			$planification->setName($resourceDB->getName());
			$em->persist($planification);
			$em->persist($planificationPeriod);
		}
		$planificationResource = new PlanificationResource($connectedUser, $planificationPeriod, $resourceDB);
		$planificationResource->setOrder($i);
		$em->persist($planificationResource);
	}

	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'planification.created.ok');
	return $this->redirectToRoute('sd_core_planification_list', array('pageNumber' => 1));
    }


	// Mise a jour des ressources à planifier
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function updateresourceAction(Planification $planification, PlanificationPeriod $planificationPeriod, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = PlanificationController::getSelectedResources($em, $resourceIDList);

	$resourcesToPlanify = PlanificationController::getResourcesToPlanify($em, $userContext->getCurrentFile(), $planification->getType(), $resourceIDList);

    return $this->render('SDCoreBundle:Planification:resource.update.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
			'selectedResources' => $selectedResources, 'selectedResourcesIDList' => $resourceIDList,
			'resourcesToPlanify' => $resourcesToPlanify));
    }


    // Edition du detail d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    */
    public function editAction(Planification $planification)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur


    $planificationPeriodRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
    $planificationPeriod = $planificationPeriodRepository->findOneBy(array('planification' => $planification), array('id' => 'DESC'));

    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $listPlanificationResources = $planificationResourceRepository->getResources($planificationPeriod);

	$resourceIDList = '';
    foreach ($listPlanificationResources as $planificationResourceDB) {
$resourceIDList = ($resourceIDList == '') ? $planificationResourceDB->getResource()->getId() : ($resourceIDList.'-'.$planificationResourceDB->getResource()->getId());
	}

    return $this->render('SDCoreBundle:Planification:edit.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
		'listPlanificationResources' => $listPlanificationResources, 'resourceIDList' => $resourceIDList));
    }
	
    // Modification d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    */
    public function modifyAction(Planification $planification, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->createForm(PlanificationType::class, $planification);
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.updated.ok');
        return $this->redirectToRoute('sd_core_planification_edit', array('planificationID' => $planification->getId()));
    }
    return $this->render('SDCoreBundle:Planification:modify.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'form' => $form->createView()));
    }
    // Suppression d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    */
    public function deleteAction(Planification $planification, Request $request)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->get('form.factory')->create();
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->remove($planification);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.deleted.ok');
        return $this->redirectToRoute('sd_core_planification_list', array('pageNumber' => 1));
    }
    return $this->render('SDCoreBundle:Planification:delete.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'form' => $form->createView()));
    }
}

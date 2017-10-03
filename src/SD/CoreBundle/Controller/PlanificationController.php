<?php
// src/SD/CoreBundle/Controller/PlanificationController.php
namespace SD\CoreBundle\Controller;

use SD\CoreBundle\Entity\UserParameter;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\ListContext;
use SD\CoreBundle\Entity\Trace;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\PlanificationResource;
use SD\CoreBundle\Entity\PlanificationLinesNDB;

use SD\CoreBundle\Form\PlanificationType;
use SD\CoreBundle\Form\PlanificationLinesNDBType;
use SD\CoreBundle\Api\ResourceApi;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PlanificationController extends Controller
{
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
                
    return $this->render('SDCoreBundle:Planification:index.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'listPlanifications' => $listPlanifications));
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
    public function init_resourceAction($type, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = ResourceApi::getSelectedResources($em, $resourceIDList);

	$resourcesToPlanify = ResourceApi::getResourcesToPlanify($em, $userContext->getCurrentFile(), $type, $resourceIDList);

    return $this->render('SDCoreBundle:Planification:resource.insert.html.twig', 
		array('userContext' => $userContext, 'type' => $type, 'selectedResources' => $selectedResources, 
		'selectedResourcesIDList' => $resourceIDList, 'resourcesToPlanify' => $resourcesToPlanify));
    }


	// Validation des ressources à planifier
    public function validate_init_resourceAction($type, $resourceIDList, Request $request)
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
    public function update_resourceAction(Planification $planification, PlanificationPeriod $planificationPeriod, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = ResourceApi::getSelectedResources($em, $resourceIDList);

	$resourcesToPlanify = ResourceApi::getResourcesToPlanify($em, $userContext->getCurrentFile(), $planification->getType(), $resourceIDList);

    return $this->render('SDCoreBundle:Planification:resource.update.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
			'selectedResources' => $selectedResources, 'selectedResourcesIDList' => $resourceIDList,
			'resourcesToPlanify' => $resourcesToPlanify));
    }


	// Validation des ressources à planifier
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function validate_update_resourceAction(Planification $planification, PlanificationPeriod $planificationPeriod, $resourceIDList, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $planificationResourceRepository->getResources($planificationPeriod);
	$resourceIDArray = explode('-', $resourceIDList);

    foreach ($planificationResources as $planificationResource) { // Parcours des ressources existantes de la période de planification
		if (array_search($planificationResource->getResource()->getId(), $resourceIDArray) === false) { // Si la ressource n'est pas dans la liste actuelle, on la supprime
			$em->remove($planificationResource);
		}
	}

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
	$i = 0;
    foreach ($resourceIDArray as $resourceID) { // Parcours des ressources sélectionnées
		$resource = $resourceRepository->find($resourceID);
		if ($resource !== null) {
			$i++; // On recherche si la ressource est déjà planifiée pour la période de planification. Si c'est le cas on met à jour l'ordre, sinon on ajoute la ressource à la période de planification
$planificationResource = $planificationResourceRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'resource' => $resource));
			if ($planificationResource === null) {
				$planificationResource = new PlanificationResource($connectedUser, $planificationPeriod, $resource);
				$planificationResource->setOrder($i);
			} else {
				$planificationResource->setOrder($i);
			}
			$em->persist($planificationResource);
		}
	}

	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'planification.resource.updated.ok');
	return $this->redirectToRoute('sd_core_planification_edit', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getId()));
    }


    // Edition du detail d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    */
    public function editLastPeriodAction(Planification $planification)
    {
    $em = $this->getDoctrine()->getManager();

    $planificationPeriodRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
    $planificationPeriod = $planificationPeriodRepository->findOneBy(array('planification' => $planification), array('id' => 'DESC'));

	return $this->redirectToRoute('sd_core_planification_edit', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID()));
    }


    // Edition du detail d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function editAction(Planification $planification)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur


    $planificationPeriodRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
    $planificationPeriod = $planificationPeriodRepository->findOneBy(array('planification' => $planification), array('id' => 'DESC'));

    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $planificationResourceRepository->getResources($planificationPeriod);

	$resourceIDList = '';
    foreach ($planificationResources as $planificationResourceDB) {
$resourceIDList = ($resourceIDList == '') ? $planificationResourceDB->getResource()->getId() : ($resourceIDList.'-'.$planificationResourceDB->getResource()->getId());
	}

    $planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
    $planificationLines = $planificationLineRepository->getLines($planificationPeriod);

    return $this->render('SDCoreBundle:Planification:edit.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
		'planificationResources' => $planificationResources, 'resourceIDList' => $resourceIDList,
		'planificationLines' => $planificationLines));
    }


	// Mise a jour des lignes de planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function lineAction(Planification $planification, PlanificationPeriod $planificationPeriod, Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
    $timetableRepository = $em->getRepository('SDCoreBundle:Timetable');

	// Premiere grille horaire du dossier
    $firstTimetable = $timetableRepository->getFirstTimetable($userContext->getCurrentFile());

	$line_MON = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'MON'));
	$line_TUE = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'TUE'));
	$line_WED = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'WED'));
	$line_THU = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'THU'));
	$line_FRI = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'FRI'));
	$line_SAT = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'SAT'));
	$line_SUN = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'SUN'));

	$planificationLinesNDB = new PlanificationLinesNDB($planificationPeriod);
	if ($line_MON->getActive()) {
		$planificationLinesNDB->setTimetableMON($line_MON->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableMON($firstTimetable);
	}
	$planificationLinesNDB->setActivateMON($line_MON->getActive());
	if ($line_TUE->getActive()) {
		$planificationLinesNDB->setTimetableTUE($line_TUE->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableTUE($firstTimetable);
	}
	$planificationLinesNDB->setActivateTUE($line_TUE->getActive());
	if ($line_WED->getActive()) {
		$planificationLinesNDB->setTimetableWED($line_WED->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableWED($firstTimetable);
	}
	$planificationLinesNDB->setActivateWED($line_WED->getActive());
	if ($line_THU->getActive()) {
		$planificationLinesNDB->setTimetableTHU($line_THU->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableTHU($firstTimetable);
	}
	$planificationLinesNDB->setActivateTHU($line_THU->getActive());
	if ($line_FRI->getActive()) {
		$planificationLinesNDB->setTimetableFRI($line_FRI->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableFRI($firstTimetable);
	}
	$planificationLinesNDB->setActivateFRI($line_FRI->getActive());
	if ($line_SAT->getActive()) {
		$planificationLinesNDB->setTimetableSAT($line_SAT->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableSAT($firstTimetable);
	}
	$planificationLinesNDB->setActivateSAT($line_SAT->getActive());
	if ($line_SUN->getActive()) {
		$planificationLinesNDB->setTimetableSUN($line_SUN->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableSUN($firstTimetable);
	}
	$planificationLinesNDB->setActivateSUN($line_SUN->getActive());

    $form = $this->createForm(PlanificationLinesNDBType::class, $planificationLinesNDB, array('current_file' => $userContext->getCurrentFile()));

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

		$line_MON->setTimetable($planificationLinesNDB->getTimetableMON());
		$line_MON->setActive($planificationLinesNDB->getActivateMON());
		$em->persist($line_MON);
		$line_TUE->setTimetable($planificationLinesNDB->getTimetableTUE());
		$line_TUE->setActive($planificationLinesNDB->getActivateTUE());
		$em->persist($line_TUE);
		$line_WED->setTimetable($planificationLinesNDB->getTimetableWED());
		$line_WED->setActive($planificationLinesNDB->getActivateWED());
		$em->persist($line_WED);
		$line_THU->setTimetable($planificationLinesNDB->getTimetableTHU());
		$line_THU->setActive($planificationLinesNDB->getActivateTHU());
		$em->persist($line_THU);
		$line_FRI->setTimetable($planificationLinesNDB->getTimetableFRI());
		$line_FRI->setActive($planificationLinesNDB->getActivateFRI());
		$em->persist($line_FRI);
		$line_SAT->setTimetable($planificationLinesNDB->getTimetableSAT());
		$line_SAT->setActive($planificationLinesNDB->getActivateSAT());
		$em->persist($line_SAT);
		$line_SUN->setTimetable($planificationLinesNDB->getTimetableSUN());
		$line_SUN->setActive($planificationLinesNDB->getActivateSUN());
		$em->persist($line_SUN);

        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.line.updated.ok');
        return $this->redirectToRoute('sd_core_planification_edit', array('planificationID' => $planification->getId(), 'planificationPeriodID' => $planificationPeriod->getId()));
    }

    return $this->render('SDCoreBundle:Planification:line.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'form' => $form->createView()));
    }


    // Modification d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function modifyAction(Planification $planification, PlanificationPeriod $planificationPeriod, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(PlanificationType::class, $planification);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà l'activite
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'planification.updated.ok');
        return $this->redirectToRoute('sd_core_planification_edit', array('planificationID' => $planification->getId(), 'planificationPeriodID' => $planificationPeriod->getId()));
    }

    return $this->render('SDCoreBundle:Planification:modify.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'form' => $form->createView()));
    }


    // Suppression d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    */
    public function deleteAction(Planification $planification, PlanificationPeriod $planificationPeriod, Request $request)
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
    return $this->render('SDCoreBundle:Planification:delete.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'form' => $form->createView()));
    }
}

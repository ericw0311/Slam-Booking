<?php
// src/SD/CoreBundle/Controller/PlanningController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\FileContext;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PlanningController extends Controller
{
    public function accesAction()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');
	$currentDate = date("Ymd");

	// Premiere planification
    $firstPlanification = $planificationRepository->getFirstPlanningPlanification($userContext->getCurrentFile(), new \DateTime());

	// Aucune planification
	if ($firstPlanification === null) {
		return $this->redirectToRoute('sd_core_planning_noplanification');
    }

	// Acces au planning d'une planification
	return $this->redirectToRoute('sd_core_planning_calendar', array('planificationID' => $firstPlanification['planificationID'], 'planificationPeriodID' => $firstPlanification['planificationPeriodID'], 'date' => $currentDate));
	}


    public function noplanificationAction()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    return $this->render('SDCoreBundle:Planning:noplanification.html.twig', array('userContext' => $userContext));
	}


    // Affichage du calendrier d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
    */
    public function calendarAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), $date);

    return $this->render('SDCoreBundle:Planning:calendar.html.twig',
		array('userContext' => $userContext, 'planifications' => $planifications, 'planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $date));
    }


    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function timetableAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), $date);

	$previousDate = clone $date;
	$previousDate->sub(new \DateInterval('P1D'));
	$nextDate = clone $date;
	$nextDate->add(new \DateInterval('P1D'));

    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $planificationResourceRepository->getResources($planificationPeriod);

    $planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));

    return $this->render('SDCoreBundle:Planning:timetable.html.twig',
		array('userContext' => $userContext, 'planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(),
			'planifications' => $planifications, 'planificationResources' => $planificationResources, 'date' => $date, 'nextDate' => $nextDate, 'previousDate' => $previousDate));
    }
}

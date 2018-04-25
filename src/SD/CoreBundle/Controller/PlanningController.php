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
use SD\CoreBundle\Entity\Ddate;

use SD\CoreBundle\Form\DdateType;

use SD\CoreBundle\Api\PlanningApi;
use SD\CoreBundle\Api\BookingApi;

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

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), new \DateTime());

	// Aucune planification
	if (count($planifications) <= 0) {
		return $this->redirectToRoute('sd_core_planning_noplanification');
    }

	// Acces au planning d'une planification
	if (count($planifications) > 1) {
		return $this->redirectToRoute('sd_core_planning_many_timetable_pp', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
	} else {
		return $this->redirectToRoute('sd_core_planning_one_timetable_pp', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
	}
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
	* @ParamConverter("date", options={"format": "Ymd"})
    */
    public function many_calendarAction(Planification $planification, \Datetime $date)
    {
	return PlanningController::calendarAction($planification, $date, 1);
    }

    // Affichage du calendrier d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
    */
    public function one_calendarAction(Planification $planification, \Datetime $date)
    {
	return PlanningController::calendarAction($planification, $date, 0);
    }

    // Affichage du calendrier d'une planification
    public function calendarAction(Planification $planification, \Datetime $date, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	PlanningApi::setCurrentCalendarPlanification($em, $connectedUser, $planification); // Le calendrier lit en base la planification courante
	PlanningApi::setCurrentCalendarMany($em, $connectedUser, $many); // Le calendrier lit en base le type d'affichage (one ou many)

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), $date);

    return $this->render('SDCoreBundle:Planning:calendar.'.($many ? 'many' : 'one').'.html.twig',
		array('userContext' => $userContext, 'planifications' => $planifications, 'planification' => $planification, 'date' => $date));
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_timetableAction(Planification $planification, \Datetime $date, Request $request)
    {
	return PlanningController::timetableAction($planification, $date, 1, $request);
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_timetableAction(Planification $planification, \Datetime $date, Request $request)
    {
	return PlanningController::timetableAction($planification, $date, 0, $request);
    }

    // Affichage de la grille horaire journaliere d'une planification
    public function timetableAction(Planification $planification, \Datetime $date, $many, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$lDate = $date;

    $ddate = new Ddate();
    $form = $this->createForm(DdateType::class, $ddate);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$lDate = $ddate->getDate();
	}

    $pRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), $lDate);

	if (count($planifications) <= 0) {
		return $this->redirectToRoute('sd_core_planning_noplanification');
    }

	$planificationFound = false;
	$planificationPeriodID = 0;
	foreach ($planifications as $i_planification) {
		if ($i_planification['ID'] == $planification->getID()) { $planificationFound = true; $planificationPeriodID = $i_planification['planificationPeriodID']; break; } // La planification en cours est dans la liste des planifications ouvertes à la date en cours.
	}

    $ppRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');

	if ($planificationFound) {
		$lPlanification = $planification;
		$planificationPeriod = $ppRepository->find($planificationPeriodID);
	} else {
		$lPlanification = $pRepository->find($planifications[0]['ID']);
		$planificationPeriod = $ppRepository->find($planifications[0]['planificationPeriodID']);
	}

	$previousDate = clone $lDate;
	$previousDate->sub(new \DateInterval('P1D'));
	$nextDate = clone $lDate;
	$nextDate->add(new \DateInterval('P1D'));

    $prRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $prRepository->getResources($planificationPeriod);

    $plRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$planificationLine = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($lDate->format('D'))));

	if ($planificationLine === null || $planificationLine->getActive() < 1) {
		return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.closed.html.twig',
	array('userContext' => $userContext, 'planification' => $lPlanification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources,
			'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'form' => $form->createView()));
	}

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());

	$bookings = BookingApi::getBookings($em, $userContext->getCurrentFile(), $lDate, $lPlanification, $planificationPeriod, $userContext->getCurrentUserFile());


    return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.opened.html.twig',
		array('userContext' => $userContext, 'planification' => $lPlanification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources, 'timetableLines' => $timetableLines,
			'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'bookings' => $bookings, 'form' => $form->createView()));
    }


    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_timetable_ppAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, Request $request)
    {
	return PlanningController::timetable_ppAction($planification, $planificationPeriod, $date, 1, $request);
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_timetable_ppAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, Request $request)
    {
	return PlanningController::timetable_ppAction($planification, $planificationPeriod, $date, 0, $request);
    }

    // Affichage de la grille horaire journaliere d'une planification A REPRENDRE
    public function timetable_ppAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $many, Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$lDate = $date;

    $ddate = new Ddate();
    $form = $this->createForm(DdateType::class, $ddate);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		$lDate = $ddate->getDate();
	}

    $pRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), $lDate);

	$previousDate = clone $lDate;
	$previousDate->sub(new \DateInterval('P1D'));
	$nextDate = clone $lDate;
	$nextDate->add(new \DateInterval('P1D'));

    $prRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $prRepository->getResources($planificationPeriod);

    $plRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$planificationLine = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($lDate->format('D'))));

	if ($planificationLine === null || $planificationLine->getActive() < 1) {
		return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.closed.html.twig',
	array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources,
			'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'form' => $form->createView()));
	}

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());

	$bookings = BookingApi::getBookings($em, $userContext->getCurrentFile(), $lDate, $planification, $planificationPeriod, $userContext->getCurrentUserFile());


    return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.opened.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources, 'timetableLines' => $timetableLines,
			'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'bookings' => $bookings, 'form' => $form->createView()));
    }
}

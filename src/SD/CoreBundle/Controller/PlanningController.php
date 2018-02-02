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
		return $this->redirectToRoute('sd_core_planning_many_timetable', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
	} else {
		return $this->redirectToRoute('sd_core_planning_one_timetable', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
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
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
    */
    public function many_calendarAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::calendarAction($planification, $planificationPeriod, $date, 1);
    }

    // Affichage du calendrier d'une planification
    /**
    * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
    */
    public function one_calendarAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::calendarAction($planification, $planificationPeriod, $date, 0);
    }

    // Affichage du calendrier d'une planification
    public function calendarAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), $date);

    return $this->render('SDCoreBundle:Planning:calendar.'.($many ? 'many' : 'one').'.html.twig',
		array('userContext' => $userContext, 'planifications' => $planifications, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'date' => $date));
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_timetableAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::timetableAction($planification, $planificationPeriod, $date, 1);
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_timetableAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::timetableAction($planification, $planificationPeriod, $date, 0);
    }

    // Affichage de la grille horaire journaliere d'une planification
    public function timetableAction(Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');

    $planifications = $planificationRepository->getPlanningPlanifications($userContext->getCurrentFile(), $date);

	$previousDate = clone $date;
	$previousDate->sub(new \DateInterval('P1D'));
	$previousWeek = clone $date;
	$previousWeek->sub(new \DateInterval('P7D'));
	$previousMonth = clone $date;
	$previousMonth->sub(new \DateInterval('P1M'));
	$nextDate = clone $date;
	$nextDate->add(new \DateInterval('P1D'));
	$nextWeek = clone $date;
	$nextWeek->add(new \DateInterval('P7D'));
	$nextMonth = clone $date;
	$nextMonth->add(new \DateInterval('P1M'));

    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $planificationResourceRepository->getResources($planificationPeriod);

    $planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));

	if ($planificationLine === null || $planificationLine->getActive() < 1) {
		return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.closed.html.twig',
	array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources,
			'date' => $date, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'nextWeek' => $nextWeek, 'previousWeek' => $previousWeek,
			'nextMonth' => $nextMonth, 'previousMonth' => $previousMonth));
	}

    $timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());

	$bookings = BookingApi::getBookings($em, $userContext->getCurrentFile(), $date, $planification, $planificationPeriod);

    return $this->render('SDCoreBundle:Planning:timetable.'.($many ? 'many' : 'one').'.opened.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'planificationLine' => $planificationLine,
			'planifications' => $planifications, 'planificationResources' => $planificationResources, 'timetableLines' => $timetableLines,
			'date' => $date, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'nextWeek' => $nextWeek, 'previousWeek' => $previousWeek,
			'nextMonth' => $nextMonth, 'previousMonth' => $previousMonth, 'bookings' => $bookings));
    }
}

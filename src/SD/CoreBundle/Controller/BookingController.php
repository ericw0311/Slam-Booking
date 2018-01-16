<?php
// src/SD/CoreBundle/Controller/BookingController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\Resource;

use SD\CoreBundle\Api\BookingApi;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BookingController extends Controller
{
    // Création de réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, 1);
    }

    // Création de réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, 0);
    }

    // Création de réservation
    public function create_Action(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$cellArray  = explode("-", $timetableLinesList);

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLinesList) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);

	$beginningTimetableLines = explode("*", $beginningTimetableLinesList);
	$beginningTimetableLineID = $beginningTimetableLines[0];

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

	list($endDateString, $endTimetableID, $endTimetableLinesList) = explode("+", $cellArray[count($cellArray)-1]);
	$endDate = date_create_from_format("Ymd", $endDateString);

	$endTimetableLines = explode("*", $endTimetableLinesList);
	$endTimetableLineID = $endTimetableLines[count($endTimetableLines)-1];

	$endTimetableLine = $ttlRepository->find($endTimetableLineID);

	return $this->render('SDCoreBundle:Booking:create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine,
	'endDate' => $endDate, 'endTimetableLine' => $endTimetableLine));
    }


    // Mise a jour de la periode de fin
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, 1);
    }

    // Mise a jour de la periode de fin
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, 0);
    }

    // Mise a jour de la periode de fin
    public function end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$cellArray  = explode("-", $timetableLinesList);

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLineID) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

    $endPeriods = BookingApi::getEndPeriods($em, $planificationPeriod, $beginningDate, $beginningTimetableLine);

	return $this->render('SDCoreBundle:Booking:period.end.create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine, 'endPeriods' => $endPeriods));
    }
}

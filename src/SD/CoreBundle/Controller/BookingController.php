<?php
// src/SD/CoreBundle/Controller/BookingController.php

namespace SD\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use SD\CoreBundle\Entity\Constants;
use SD\CoreBundle\Entity\UserContext;
use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\Resource;
use SD\CoreBundle\Entity\Booking;

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
    public function many_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, 1);
    }

    // Création de réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, 0);
    }

    // Création de réservation
    public function create_Action(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $many)
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

	// Utilisateurs
	$userFileIDArray = explode("-", $userFileIDList);

	$userFiles = array();
	$userFileRepository = $em->getRepository('SDCoreBundle:UserFile');

	foreach ($userFileIDArray as $userFileID) {

		$userFile = $userFileRepository->find($userFileID);
		if ($userFile !== null) {
			$userFiles[] = $userFile;
		}
	}

	return $this->render('SDCoreBundle:Booking:create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine,
	'endDate' => $endDate, 'endTimetableLine' => $endTimetableLine,
	'userFiles' => $userFiles, 'userFileIDList' => $userFileIDList));
    }


    // Mise a jour de la periode de fin
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, 1);
    }

    // Mise a jour de la periode de fin
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, 0);
    }

    // Mise a jour de la periode de fin
    public function end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$cellArray  = explode("-", $timetableLinesList);

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLineID) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

    $endPeriods = BookingApi::getEndPeriods($em, $planificationPeriod, $beginningDate, $beginningTimetableLine, $firstDateNumber, $nextFirstDateNumber);

	// Calucl du premier jour affiché précedent
	$previousFirstDateNumber = ($firstDateNumber < Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED) ? 0 : ($firstDateNumber - Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED);

	return $this->render('SDCoreBundle:Booking:period.end.create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine, 
	'endPeriods' => $endPeriods, 'firstDateNumber' => $firstDateNumber, 'previousFirstDateNumber' => $previousFirstDateNumber, 'nextFirstDateNumber' => $nextFirstDateNumber,
	'userFileIDList' => $userFileIDList));
    }


    // Mise a jour de la liste des utilisateurs
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDInitialList, $userFileIDList, 1);
    }

    // Mise a jour de la liste des utilisateurs
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDInitialList, $userFileIDList, 0);
    }

    // Mise a jour de la liste des utilisateurs
    public function user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDInitialList, $userFileIDList, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$selectedUserFiles = BookingApi::getSelectedUserFiles($em, $userFileIDList);
	
	$availableUserFiles = BookingApi::initAvailableUserFiles($em, $userContext->getCurrentFile(), $userFileIDList);

	return $this->render('SDCoreBundle:Booking:user.files.create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
'selectedUserFiles' => $selectedUserFiles, 'availableUserFiles' => $availableUserFiles, 'userFileIDList' => $userFileIDList, 'userFileIDInitialList' => $userFileIDInitialList));
    }

	// Validation de la création d'une réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
    public function many_validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, Request $request)
    {
	return BookingController::validate_createAction($planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $request, 1);
    }


    // Validation de la création d'une réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
	public function one_validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, Request $request)
    {
	return BookingController::validate_createAction($planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $request, 0);
    }


    // Validation de la création d'une réservation
    public function validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, Request $request, $many)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	$booking = new Booking($connectedUser, $userContext->getCurrentFile());
	
	$cellArray  = explode("-", $timetableLinesList);

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLinesList) = explode("+", $cellArray[0]);

	$beginningTimetableLines = explode("*", $beginningTimetableLinesList);
	$beginningTimetableLineID = $beginningTimetableLines[0];

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

	$booking->setBeginningDate(date_create_from_format('YmdHi', $beginningDateString.$beginningTimetableLine->getBeginningTime()->format('Hi')));

	list($endDateString, $endTimetableID, $endTimetableLinesList) = explode("+", $cellArray[count($cellArray)-1]);

	$endTimetableLines = explode("*", $endTimetableLinesList);
	$endTimetableLineID = $endTimetableLines[count($endTimetableLines)-1];

	$endTimetableLine = $ttlRepository->find($endTimetableLineID);

	$booking->setEndDate(date_create_from_format('YmdHi', $endDateString.$endTimetableLine->getEndTime()->format('Hi')));
	
	$em->persist($booking);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'booking.created.ok');

	return $this->redirectToRoute('sd_core_planning_'.($many ? 'many' : 'one').'_timetable',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $beginningDateString));
    }
}

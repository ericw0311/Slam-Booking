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
use SD\CoreBundle\Entity\BookingLine;
use SD\CoreBundle\Entity\BookingUser;
use SD\CoreBundle\Entity\BookingLabel;

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
    public function many_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, 1);
    }

    // Création de réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::create_Action($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, 0);
    }

    // Création de réservation
    public function create_Action(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	BookingApi::getBookingLinesUrlBeginningAndEndPeriod($em, $timetableLinesList, $beginningDate, $beginningTimetableLine, $endDate, $endTimetableLine);

	// Utilisateurs
	$userFiles = BookingApi::getUserFiles($em, $userFileIDList);

	// Etiquettes
	$labels = BookingApi::getLabels($em, $labelIDList);

	return $this->render('SDCoreBundle:Booking:create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource,
	'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine,
	'endDate' => $endDate, 'endTimetableLine' => $endTimetableLine,
	'userFiles' => $userFiles, 'userFileIDList' => $userFileIDList,
	'labels' => $labels, 'labelIDList' => $labelIDList, 'noteID' => $noteID));
    }

    // Initialisation de la mise à jour de réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_init_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date)
    {
	return BookingController::init_update_Action($booking, $planification, $planificationPeriod, $resource, $date, 1);
    }

    // Initialisation de la mise à jour de réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_init_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date)
    {
	return BookingController::init_update_Action($booking, $planification, $planificationPeriod, $resource, $date, 0);
    }

    // Initialisation de la mise à jour de réservation
    public function init_update_Action(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $many)
    {
    $em = $this->getDoctrine()->getManager();

	$timetableLinesList = BookingApi::getBookingLinesUrl($em, $booking);
	$userFileIDList = BookingApi::getBookingUsersUrl($em, $booking);
	$labelIDList = BookingApi::getBookingLabelsUrl($em, $booking);

	return $this->redirectToRoute('sd_core_booking_'.($many ? 'many' : 'one').'_update',
		array('bookingID' => $booking->getID(),
		'planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(),
		'resourceID' => $resource->getID(), 'date' => $date->format('Ymd'),
		'timetableLinesList' => $timetableLinesList, 'userFileIDList' => $userFileIDList, 'labelIDList' => $labelIDList, 'noteID' => 0));
    }

    // Mise à jour de réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::update_Action($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, 1);
    }

    // Mise à jour de réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::update_Action($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, 0);
    }

    // Mise à jour de réservation
    public function update_Action(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	BookingApi::getBookingLinesUrlBeginningAndEndPeriod($em, $timetableLinesList, $beginningDate, $beginningTimetableLine, $endDate, $endTimetableLine);

	// Utilisateurs
	$userFiles = BookingApi::getUserFiles($em, $userFileIDList);

	// Etiquettes
	$labels = BookingApi::getLabels($em, $labelIDList);

	return $this->render('SDCoreBundle:Booking:update.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'booking' => $booking, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource,
	'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine,
	'endDate' => $endDate, 'endTimetableLine' => $endTimetableLine,
	'userFiles' => $userFiles, 'userFileIDList' => $userFileIDList,
	'labels' => $labels, 'labelIDList' => $labelIDList, 'noteID' => $noteID));
    }

    // Mise a jour de la periode de fin (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, 1);
    }

    // Mise a jour de la periode de fin (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::end_period_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, 0);
    }

    // Mise a jour de la periode de fin (en création de réservation)
    public function end_period_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$cellArray  = explode("-", $timetableLinesList);

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLineID) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

    $endPeriods = BookingApi::getEndPeriods($em, $planificationPeriod, $resource, $beginningDate, $beginningTimetableLine, 0, $firstDateNumber, $nextFirstDateNumber);

	// Calucl du premier jour affiché précedent
	$previousFirstDateNumber = ($firstDateNumber < Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED) ? 0 : ($firstDateNumber - Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED);

	return $this->render('SDCoreBundle:Booking:period.end.create.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine, 
	'endPeriods' => $endPeriods, 'firstDateNumber' => $firstDateNumber, 'previousFirstDateNumber' => $previousFirstDateNumber, 'nextFirstDateNumber' => $nextFirstDateNumber,
	'userFileIDList' => $userFileIDList, 'labelIDList' => $labelIDList, 'noteID' => $noteID));
    }

    // Mise a jour de la periode de fin (en mise à jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_end_period_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::end_period_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, 1);
    }

    // Mise a jour de la periode de fin (en mise à jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_end_period_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID)
    {
	return BookingController::end_period_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, 0);
    }

    // Mise a jour de la periode de fin (en mise à jour de réservation)
    public function end_period_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $firstDateNumber, $userFileIDList, $labelIDList, $noteID, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$cellArray  = explode("-", $timetableLinesList);

    $ttlRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLineID) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);

	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);

    $endPeriods = BookingApi::getEndPeriods($em, $planificationPeriod, $resource, $beginningDate, $beginningTimetableLine, $booking->getID(), $firstDateNumber, $nextFirstDateNumber);

	// Calucl du premier jour affiché précedent
	$previousFirstDateNumber = ($firstDateNumber < Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED) ? 0 : ($firstDateNumber - Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED);

	return $this->render('SDCoreBundle:Booking:period.end.update.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'booking' => $booking, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
	'resource' => $resource, 'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine, 
	'endPeriods' => $endPeriods, 'firstDateNumber' => $firstDateNumber, 'previousFirstDateNumber' => $previousFirstDateNumber, 'nextFirstDateNumber' => $nextFirstDateNumber,
	'userFileIDList' => $userFileIDList, 'labelIDList' => $labelIDList, 'noteID' => $noteID));
    }

    // Mise a jour de la liste des utilisateurs (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, 1);
    }

    // Mise a jour de la liste des utilisateurs (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, 0);
    }

    // Mise a jour de la liste des utilisateurs (en création de réservation)
    public function user_files_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$selectedUserFiles = BookingApi::getSelectedUserFiles($em, $userFileIDList);
	
	$availableUserFiles = BookingApi::initAvailableUserFiles($em, $userContext->getCurrentFile(), $userFileIDList);

	return $this->render('SDCoreBundle:Booking:user.files.create.'.($many ? 'many' : 'one').'.html.twig',
	array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date,
	'timetableLinesList' => $timetableLinesList, 'labelIDList' => $labelIDList, 'noteID' => $noteID,
	'selectedUserFiles' => $selectedUserFiles, 'availableUserFiles' => $availableUserFiles, 'userFileIDList' => $userFileIDList, 'userFileIDInitialList' => $userFileIDInitialList));
    }

    // Mise a jour de la liste des utilisateurs (en mise a jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_user_files_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, 1);
    }

    // Mise a jour de la liste des utilisateurs (en mise a jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_user_files_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList)
    {
	return BookingController::user_files_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, 0);
    }

    // Mise a jour de la liste des utilisateurs (en mise à jour de réservation)
    public function user_files_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $labelIDList, $noteID, $userFileIDInitialList, $userFileIDList, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$selectedUserFiles = BookingApi::getSelectedUserFiles($em, $userFileIDList);
	
	$availableUserFiles = BookingApi::initAvailableUserFiles($em, $userContext->getCurrentFile(), $userFileIDList);

	return $this->render('SDCoreBundle:Booking:user.files.update.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'booking' => $booking, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource,
	'date' => $date, 'timetableLinesList' => $timetableLinesList, 'labelIDList' => $labelIDList, 'noteID' => $noteID,
	'selectedUserFiles' => $selectedUserFiles, 'availableUserFiles' => $availableUserFiles, 'userFileIDList' => $userFileIDList, 'userFileIDInitialList' => $userFileIDInitialList));
    }


	// Mise a jour de la liste des étiquettes (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
	public function many_labels_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList)
    {
	return BookingController::labels_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, 1);
    }
	
    // Mise a jour de la liste des étiquettes (en création de réservation)
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
	public function one_labels_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList)
    {
	return BookingController::labels_createAction($planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, 0);
    }

    // Mise a jour de la liste des étiquettes (en création de réservation)
    public function labels_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedLabels = BookingApi::getSelectedLabels($em, $labelIDList);

	$availableLabels = BookingApi::initAvailableLabels($em, $userContext->getCurrentFile(), $labelIDList);

	return $this->render('SDCoreBundle:Booking:labels.create.'.($many ? 'many' : 'one').'.html.twig',
	array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource, 'date' => $date,
	'timetableLinesList' => $timetableLinesList, 'userFileIDList' => $userFileIDList, 'noteID' => $noteID,
	'selectedLabels' => $selectedLabels, 'availableLabels' => $availableLabels,
	'labelIDList' => $labelIDList, 'labelIDInitialList' => $labelIDInitialList));
    }

    // Mise a jour de la liste des étiquettes (en mise a jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_labels_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList)
    {
	return BookingController::labels_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, 1);
    }

    // Mise a jour de la liste des étiquettes (en mise a jour de réservation)
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_labels_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList)
    {
	return BookingController::labels_updateAction($booking, $planification, $planificationPeriod, $resource, $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, 0);
    }

    // Mise a jour de la liste des étiquettes (en mise à jour de réservation)
    public function labels_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, $timetableLinesList, $userFileIDList, $noteID, $labelIDInitialList, $labelIDList, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$selectedLabels = BookingApi::getSelectedLabels($em, $labelIDList);

	$availableLabels = BookingApi::initAvailableLabels($em, $userContext->getCurrentFile(), $labelIDList);

	return $this->render('SDCoreBundle:Booking:labels.update.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'booking' => $booking, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource,
	'date' => $date, 'timetableLinesList' => $timetableLinesList, 'userFileIDList' => $userFileIDList, 'noteID' => $noteID,
	'selectedLabels' => $selectedLabels, 'availableLabels' => $availableLabels, 'labelIDList' => $labelIDList, 'labelIDInitialList' => $labelIDInitialList));
    }

	// Validation de la création d'une réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
    public function many_validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request)
    {
	return BookingController::validate_createAction($planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $request, 1);
    }

    // Validation de la création d'une réservation
    /**
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
	public function one_validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request)
    {
	return BookingController::validate_createAction($planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $request, 0);
    }

	// Validation de la création d'une réservation
    public function validate_createAction(Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request, $many)
    {
	$logger = $this->get('logger'); $logger->info('DBG 1');

	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$plRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
    $tRepository = $em->getRepository('SDCoreBundle:Timetable');
    $tlRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $ufRepository = $em->getRepository('SDCoreBundle:UserFile');
    $lRepository = $em->getRepository('SDCoreBundle:Label');

	$booking = new Booking($connectedUser, $userContext->getCurrentFile(), $planification, $resource);

	$urlArray  = explode("-", $timetableLinesList);

	list($beginningDateString, $beginningTimetableID, $beginningTimetableLinesList) = explode("+", $urlArray[0]);

	$beginningTimetableLines = explode("*", $beginningTimetableLinesList);
	$beginningTimetableLineID = $beginningTimetableLines[0];

	$beginningTimetableLine = $tlRepository->find($beginningTimetableLineID);

	$booking->setBeginningDate(date_create_from_format('YmdHi', $beginningDateString.$beginningTimetableLine->getBeginningTime()->format('Hi')));

	list($endDateString, $endTimetableID, $endTimetableLinesList) = explode("+", $urlArray[count($urlArray)-1]);

	$endTimetableLines = explode("*", $endTimetableLinesList);
	$endTimetableLineID = $endTimetableLines[count($endTimetableLines)-1];

	$endTimetableLine = $tlRepository->find($endTimetableLineID);

	$booking->setEndDate(date_create_from_format('YmdHi', $endDateString.$endTimetableLine->getEndTime()->format('Hi')));

	$em->persist($booking);

	// Lignes de réservation
	$timetableLines = BookingApi::getTimetableLines($timetableLinesList);

	foreach ($timetableLines as $timetableLineString) {
		list($dateString, $timetableID, $timetableLineID) = explode("-", $timetableLineString);
		
		$date = date_create_from_format('Ymd', $dateString);
		
		$bookingLine = new BookingLine($connectedUser, $booking, $resource);
		$bookingLine->setDate($date);
		$bookingLine->setPlanification($planification);
		$bookingLine->setPlanificationPeriod($planificationPeriod);
		$bookingLine->setPlanificationLine($plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D')))));
		$bookingLine->setTimetable($tRepository->find($timetableID));
		$bookingLine->setTimetableLine($tlRepository->find($timetableLineID));
		$em->persist($bookingLine);
	}

	// Utilisateurs de réservation
	$order = 0;
	$userFileIDArray = explode("-", $userFileIDList);

	foreach ($userFileIDArray as $userFileID) {
		$bookingUser = new BookingUser($connectedUser, $booking, $ufRepository->find($userFileID));
		$bookingUser->setOrder(++$order);
		$em->persist($bookingUser);
	}

	// Etiquettes de réservation
	$labelIDArray = array();
	if ($labelIDList != '0') {
		$labelIDArray = explode("-", $labelIDList);
	}
	$order = 0;

	foreach ($labelIDArray as $labelID) {
		$logger->info('DBG 4 _'.$labelID.'_');
		$bookingLabel = new BookingLabel($connectedUser, $booking, $lRepository->find($labelID));
		$bookingLabel->setOrder(++$order);
		$em->persist($bookingLabel);
	}

	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'booking.created.ok');

	return $this->redirectToRoute('sd_core_planning_'.($many ? 'many' : 'one').'_timetable_pp',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $beginningDateString));
    }

	// Validation de la mise à jour d'une réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
	public function many_validate_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request)
	{
	return BookingController::validate_updateAction($booking, $planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $request, 1);
	}

	// Validation de la mise à jour d'une réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	*/
	public function one_validate_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request)
    {
	return BookingController::validate_updateAction($booking, $planification, $planificationPeriod, $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, $request, 0);
    }

	// Validation de la création d'une réservation
    public function validate_updateAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, $timetableLinesList, $userFileIDList, $labelIDList, $noteID, Request $request, $many)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$plRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
    $tRepository = $em->getRepository('SDCoreBundle:Timetable');
    $tlRepository = $em->getRepository('SDCoreBundle:TimetableLine');
    $ufRepository = $em->getRepository('SDCoreBundle:UserFile');
    $bliRepository = $em->getRepository('SDCoreBundle:BookingLine');
    $buRepository = $em->getRepository('SDCoreBundle:BookingUser');
    $blaRepository = $em->getRepository('SDCoreBundle:BookingLabel');
    $lRepository = $em->getRepository('SDCoreBundle:Label');
	
	$urlArray  = explode("-", $timetableLinesList);
	
	// Mise à jour des dates heures mini et maxi de la réservation.
	list($beginningDateString, $beginningTimetableID, $beginningTimetableLinesList) = explode("+", $urlArray[0]);
	$beginningTimetableLines = explode("*", $beginningTimetableLinesList);
	$beginningTimetableLineID = $beginningTimetableLines[0];
	$beginningTimetableLine = $tlRepository->find($beginningTimetableLineID);
	$booking->setBeginningDate(date_create_from_format('YmdHi', $beginningDateString.$beginningTimetableLine->getBeginningTime()->format('Hi')));
	list($endDateString, $endTimetableID, $endTimetableLinesList) = explode("+", $urlArray[count($urlArray)-1]);
	$endTimetableLines = explode("*", $endTimetableLinesList);
	$endTimetableLineID = $endTimetableLines[count($endTimetableLines)-1];
	$endTimetableLine = $tlRepository->find($endTimetableLineID);
	$booking->setEndDate(date_create_from_format('YmdHi', $endDateString.$endTimetableLine->getEndTime()->format('Hi')));
	$em->persist($booking);

	$url_timetableLinesString = BookingApi::getTimetableLines($timetableLinesList);
	
	// Parcours des lignes de réservation dans l'ordre chronologique.
	$bookingLines = $bliRepository->findBy(array('booking' => $booking), array('date' => 'asc', 'timetable' => 'asc', 'timetableLine' => 'asc')); // TPRR. Voir le champ date (date ou ddate)
	
	foreach ($bookingLines as $bookingLine) {
		$booking_timetableLineString = $bookingLine->getDate()->format('Ymd').'-'.$bookingLine->getTimetable()->getID().'-'.$bookingLine->getTimetableLine()->getID();
		
		if (!in_array($booking_timetableLineString, $url_timetableLinesString)) { // La ligne de réservation n'appartient pas aux lignes de l'Url. Elle est supprimée.
			$em->remove($bookingLine);
		}
	}

	// Parcours des lignes de réservation de l'Url.
	foreach ($url_timetableLinesString as $url_timetableLineString) {

		list($dateString, $timetableID, $timetableLineID) = explode("-", $url_timetableLineString);
		$date = date_create_from_format('Ymd', $dateString);
		// Recherche de la ligne de réservation en base.
		$bookingLineDB = $bliRepository->findOneBy(array('resource' => $resource, 'date' => $date, 'timetable' => $tRepository->find($timetableID), 'timetableLine' => $tlRepository->find($timetableLineID)));

		if ($bookingLineDB === null) { // La ligne de réservation n'existe pas en base, on la crée.
			$bookingLine = new BookingLine($connectedUser, $booking, $resource);
			$bookingLine->setDate($date);
			$bookingLine->setPlanification($planification);
			$bookingLine->setPlanificationPeriod($planificationPeriod);
			$bookingLine->setPlanificationLine($plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D')))));
			$bookingLine->setTimetable($tRepository->find($timetableID));
			$bookingLine->setTimetableLine($tlRepository->find($timetableLineID));
			$em->persist($bookingLine);
		}
	}
	
	// Tableau des utilisateurs de l'Url
	$url_userFileID = explode("-", $userFileIDList);

	// Parcours des utilisateurs de la réservation.
	$bookingUsers = $buRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	
	foreach ($bookingUsers as $bookingUser) {
		if (!in_array($bookingUser->getUserFile()->getID(), $url_userFileID)) { // L'utilisateur n'appartient pas a la liste de l'Url. Il est supprimé.
			$em->remove($bookingUser);
		}
	}

	$order = 0;
	// Parcours des utilisateurs de l'Url.
	foreach ($url_userFileID as $userFileID) {
		$bookingUser = $buRepository->findOneBy(array('booking' => $booking, 'userFile' => $ufRepository->find($userFileID)));

		if ($bookingUser === null) { // L'utilisateur n'est pas rattaché en base à la réservation --> on crée le lien.
			$bookingUser = new BookingUser($connectedUser, $booking, $ufRepository->find($userFileID));
		}
		$bookingUser->setOrder(++$order); // Pour tous les utilisateurs de l'Url, on met à jour le numéro d'ordre
		$em->persist($bookingUser);
	}

	// Tableau des étiquettes de l'Url
	$url_labelID = array();
	if ($labelIDList != '0') { // La chaine '0' équivaut à une chaine vide
		$url_labelID = explode("-", $labelIDList);
	}

	// Parcours des étiquettes de la réservation.
	$bookingLabels = $blaRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	
	foreach ($bookingLabels as $bookingLabel) {
		if (!in_array($bookingLabel->getLabel()->getID(), $url_labelID)) { // L'étiquette n'appartient pas a la liste de l'Url. Elle est supprimée.
			$em->remove($bookingLabel);
		}
	}

	$order = 0;
	// Parcours des étiquettes de l'Url.
	foreach ($url_labelID as $labelID) {
		$bookingLabel = $blaRepository->findOneBy(array('booking' => $booking, 'label' => $lRepository->find($labelID)));

		if ($bookingLabel === null) { // L'étiquette n'est pas rattachée en base à la réservation --> on crée le lien.
			$bookingLabel = new BookingLabel($connectedUser, $booking, $lRepository->find($labelID));
		}
		$bookingLabel->setOrder(++$order); // Pour toutes les étiquettes de l'Url, on met à jour le numéro d'ordre
		$em->persist($bookingLabel);
	}

	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'booking.updated.ok');
	return $this->redirectToRoute('sd_core_planning_'.($many ? 'many' : 'one').'_timetable_pp',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $beginningDateString));
    }

    // Suppression d'une réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function many_deleteAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, Request $request)
    {
	return BookingController::delete_Action($booking, $planification, $planificationPeriod, $resource, $date, $request, 1);
    }

    // Suppression d'une réservation
    /**
	* @ParamConverter("booking", options={"mapping": {"bookingID": "id"}})
	* @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
    * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
    * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
	* @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one_deleteAction(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, Request $request)
    {
	return BookingController::delete_Action($booking, $planification, $planificationPeriod, $resource, $date, $request, 0);
    }

    // Suppression d'une réservation
    public function delete_Action(Booking $booking, Planification $planification, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $date, Request $request, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$timetableLinesList = BookingApi::getBookingLinesUrl($em, $booking);
	BookingApi::getBookingLinesUrlBeginningAndEndPeriod($em, $timetableLinesList, $beginningDate, $beginningTimetableLine, $endDate, $endTimetableLine);

	$userFileIDList = BookingApi::getBookingUsersUrl($em, $booking);

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

    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        // Inutile de persister ici, Doctrine connait déjà la reservation
        $em->remove($booking);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'booking.deleted.ok');
		return $this->redirectToRoute('sd_core_planning_'.($many ? 'many' : 'one').'_timetable_pp',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $date->format('Ymd')));
    }

	return $this->render('SDCoreBundle:Booking:delete.'.($many ? 'many' : 'one').'.html.twig',
array('userContext' => $userContext, 'booking' => $booking, 'form' => $form->createView(),
	'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'resource' => $resource,
	'date' => $date, 'timetableLinesList' => $timetableLinesList,
	'beginningDate' => $beginningDate, 'beginningTimetableLine' => $beginningTimetableLine,
	'endDate' => $endDate, 'endTimetableLine' => $endTimetableLine,
	'userFiles' => $userFiles, 'userFileIDList' => $userFileIDList));
    }
}

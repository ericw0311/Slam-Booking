<?php
// src/SD/CoreBundle/Api/BookingApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\Resource;
use SD\CoreBundle\Entity\TimetableLine;
use SD\CoreBundle\Entity\BookingDateNDB;
use SD\CoreBundle\Entity\BookingPeriodNDB;
use SD\CoreBundle\Entity\UserFileNDBAdd;
use SD\CoreBundle\Entity\BookingNDB;
use SD\CoreBundle\Entity\UserFileNDBSelected;
use SD\CoreBundle\Entity\Constants;

class BookingApi
{
	// firstDateNumber: Premiere date affichee
	// bookingID: Identifient de la réservation mise à jour (0 si création de réservation)
	static function getEndPeriods($em, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $beginningDate, TimetableLine $beginningTimetableLine, $bookingID, $firstDateNumber, &$nextFirstDateNumber)
	{
	$plRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$tlRepository = $em->getRepository('SDCoreBundle:TimetableLine');
	$blRepository = $em->getRepository('SDCoreBundle:BookingLine');
	$endPeriods = array();
	$dateIndex = 0;
	$numberDates = 0;
	$numberPeriods = 0;
	$continue = true;

	while ($continue) {
		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$dateIndex.'D'));
		$planificationLine = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));
		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			$numberDates++;
			$endDate = new BookingDateNDB($date);
			if ($dateIndex > 0) {
				$timetableLines = $tlRepository->getTimetableLines($planificationLine->getTimetable());
			} else {
				$timetableLines = $tlRepository->getCurrentAndNextTimetableLines($planificationLine->getTimetable(), $beginningTimetableLine->getID());
			}
			$dateTimetableLinesList = $date->format('Ymd').'+'.$planificationLine->getTimetable()->getID();
			$firstDatePeriod = true; // Premiere période de la date
			foreach ($timetableLines as $timetableLine) {
				if ($continue) {
	$dateTimetableLinesList = ($firstDatePeriod) ? ($dateTimetableLinesList.'+'.$timetableLine->getID()) : ($dateTimetableLinesList.'*'.$timetableLine->getID());
	$periodTimetableLinesList = ($numberDates <= 1) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);
	
	// Recherche d'une ligne de réservation existante.
	$bookingLineDB = $blRepository->findOneBy(array('resource' => $resource, 'date' => $date, 'timetable' => $timetableLine->getTimetable(), 'timetableLine' => $timetableLine));
	if ($bookingLineDB === null || $bookingLineDB->getBooking()->getID() == $bookingID) { // La ressource n'est pas réservée pour le créneau (ou bien on est en mise à jour de réservation et le créneau est réservé pour la réservation à mettre à jour).
					$status = "OK";
					$numberPeriods++;
	} else { // Une réservation existe sur ce créneau (ou une autre réservation que celle à mettre à jour)
					$status = "KO";
					$continue = false;
	}
					$endPeriod = new BookingPeriodNDB($timetableLine, $periodTimetableLinesList, $status);
					$endDate->addPeriod($endPeriod);
				}
			
				$firstDatePeriod = false;
				if ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $continue = false; } // Nombre maximum de periodes pour une reservation atteint
			}
			
			if ($numberDates >= $firstDateNumber) { $endPeriods[] = $endDate; }
			
			$timetableLinesList = ($numberDates <= 1) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);
			if ($numberDates >= ($firstDateNumber - 1 + Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED)) { $continue = false; } // Nombre maximum de dates affichées atteint
		}
		$dateIndex++;
	}
	// Premiere date affichee suivante: 0 si on a atteint le nombre de periodes de réservation maximum, calculée sinon
	$nextFirstDateNumber = ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES) ? 0 : ($firstDateNumber + Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED);
	return $endPeriods;
    }
    
	// Retourne un tableau des utilisateurs sélectionnés
	// resourceIDList: Liste des ID des utilisateurs sélectionnés
	static function getSelectedUserFiles($em, $userFileIDList)
	{
	$userFileIDArray = explode('-', $userFileIDList);
    $ufRepository = $em->getRepository('SDCoreBundle:UserFile');
	$selectedUserFiles = array();
	$i = 0;
    foreach ($userFileIDArray as $userFileID) {
		$userFileDB = $ufRepository->find($userFileID);
		if ($userFileDB !== null) {
			$userFile = new UserFileNDBSelected(); // classe UserFile incluant les infos spécifiques aux utilisateurs sélectionnés
			$userFile->setId($userFileDB->getId());
			$userFile->setLastName($userFileDB->getLastName());
			$userFile->setFirstName($userFileDB->getFirstName());
			$userFile->setAdministrator($userFileDB->getAdministrator());
			$userFileIDArray_tprr = $userFileIDArray;
			unset($userFileIDArray_tprr[$i]);
			$userFile->setUserFileIDList_unselect(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur désélectionne l'utilisateur
			if (count($userFileIDArray) > 1) {
				if ($i > 0) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i-1];
					$userFileIDArray_tprr[$i-1] = $userFileID;
					$userFile->setUserFileIDList_sortBefore(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur remonte l'utilisateur dans l'ordre de tri
				}
				if ($i < count($userFileIDArray)-1) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i+1];
					$userFileIDArray_tprr[$i+1] = $userFileID;
					$userFile->setUserFileIDList_sortAfter(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur redescend l'utilisateur dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedUserFiles, $userFile);
		}
	}
	return $selectedUserFiles;
    }


	// Retourne un tableau des utilisateurs pouvant être ajouté à une réservation
	static function getAvailableUserFiles($userFilesDB, $selectedUserFileIDList)
    {
	$selectedUserFileIDArray = explode('-', $selectedUserFileIDList);
	$availableUserFiles = array();
    foreach ($userFilesDB as $userFileDB) {
		if (array_search($userFileDB->getId(), $selectedUserFileIDArray) === false) {
			$userFile = new UserFileNDBAdd(); // classe UserFile incluant les infos spécifiques aux utilisateurs pouvant être ajoutés à la sélection
			$userFile->setId($userFileDB->getId());
			$userFile->setLastName($userFileDB->getLastName());
			$userFile->setFirstName($userFileDB->getFirstName());
			$userFile->setAdministrator($userFileDB->getAdministrator());
			$userFile->setUserFileIDList_select(($selectedUserFileIDList == '') ? $userFileDB->getId() : ($selectedUserFileIDList.'-'.$userFileDB->getId())); // Liste des utilisateurs sélectionnés si l'utilisateur sélectionne l'utilisateur
			array_push($availableUserFiles, $userFile);
		}
	}
	return $availableUserFiles;
    }


	// Retourne un tableau des ressources à planifier (initialisation de planification)
	static function initAvailableUserFiles($em, \SD\CoreBundle\Entity\File $file, $selectedUserFileIDList)
	{
	$ufRepository = $em->getRepository('SDCoreBundle:UserFile');

	$userFilesDB = $ufRepository->getUserFiles($file);
	return BookingApi::getAvailableUserFiles($userFilesDB, $selectedUserFileIDList);
	}


	// Convertit une URL comprenant une liste de grilles horaires (pour réservation) en un tableau de grilles horaires
	static function getTimetableLines($timetableLinesUrl)
	{
	$timetableLineArray = array();
	$urlArray  = explode("-", $timetableLinesUrl);

	foreach ($urlArray as $urlDate) {

		list($dateString, $timetableID, $timetableLinesList) = explode("+", $urlDate);	
		$timetableLineIDArray = explode("*", $timetableLinesList);

		foreach ($timetableLineIDArray as $timetableLineID) {
			$timetableLineArray[] = ($dateString.'-'.$timetableID.'-'.$timetableLineID);
		}
	}
	return $timetableLineArray;
	}


	static function getBookings($em, \SD\CoreBundle\Entity\File $file, \Datetime $date, \SD\CoreBundle\Entity\Planification $planification, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod, \SD\CoreBundle\Entity\UserFile $currentUserFile)
	{
	$bRepository = $em->getRepository('SDCoreBundle:Booking');
	$buRepository = $em->getRepository('SDCoreBundle:BookingUser');

	$bookingsDB = $bRepository->getBookings($file, $date, $planification, $planificationPeriod);
	$bookings = array();

	if (count($bookingsDB) <= 0) {
		return $bookings;
	}

	$evenResourcesID = BookingApi::getEvenPlanifiedResourcesID($em, $planificationPeriod);

	$memo_bookingID = 0;
	$memo_resourceID = 0;
	$currentBookingHeaderKey = "";
	$bookingTimetableLinesCount = 0; // Compteur des lignes de la reservation courante.
	$resourceBookingCount = 0; // Compteur des reservations de la ressource courante.

	foreach ($bookingsDB as $booking) {

		$key = $booking['date']->format('Ymd').'-'.$booking['planificationID'].'-'.$booking['planificationPeriodID'].'-'.$booking['planificationLineID'].'-'.$booking['resourceID'].'-'.$booking['timetableID'].'-'.$booking['timetableLineID'];

		if ($memo_bookingID > 0 && $booking['bookingID'] <> $memo_bookingID) { // On a parcouru une reservation.
			$bookings[$currentBookingHeaderKey]->setNumberTimetableLines($bookingTimetableLinesCount);
			$bookings[$currentBookingHeaderKey]->setUserFiles(BookingApi::getBookingUsersArray($em, $bRepository->find($memo_bookingID), $currentUserFile));
			$bookingTimetableLinesCount = 0;
			$resourceBookingCount++;
		}

		if ($booking['resourceID'] <> $memo_resourceID) { // On change de ressource.
			$resourceBookingCount = 0;
		}

		$bookingTimetableLinesCount++;

		if ($booking['bookingID'] <> $memo_bookingID) {
			$type = 'H';
			$currentBookingHeaderKey = $key;
		} else {
			$type = 'L';
		}

		$cellClass = (in_array($booking['resourceID'], $evenResourcesID) ? ((($resourceBookingCount % 2) < 1) ? 'success' : 'warning') : ((($resourceBookingCount % 2) < 1) ? 'info' : 'danger'));

		$bookingNDB = new BookingNDB($booking['bookingID'], $type, $cellClass);
		$bookings[$key] = $bookingNDB;

		$memo_bookingID = $booking['bookingID'];
		$memo_resourceID = $booking['resourceID'];
	}

	$bookings[$currentBookingHeaderKey]->setNumberTimetableLines($bookingTimetableLinesCount); // Derniere reservation
	$bookings[$currentBookingHeaderKey]->setUserFiles(BookingApi::getBookingUsersArray($em, $bRepository->find($memo_bookingID), $currentUserFile));
	return $bookings;
	}

	// Retourne un tableau des ressources paires d'une periode de planification
	static function getEvenPlanifiedResourcesID($em, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
	{
    $prRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $planificationResources = $prRepository->getResources($planificationPeriod);

	$resources = array();
	$even = false;

	foreach ($planificationResources as $planificationResource) {

		if ($even) {
			$resources[] = $planificationResource->getResource()->getID();
			$even = false;
		} else {
			$even = true;
		}
	}
	
	return $resources;
	}


	// Retourne une chaine correspondant à la liste des creneaux horaires d'une réservation
	static function getBookingLinesUrl($em, \SD\CoreBundle\Entity\Booking $booking)
	{
	$blRepository = $em->getRepository('SDCoreBundle:BookingLine');
	$bookingLinesDB = $blRepository->getBookingLines($booking);
	if (count($bookingLinesDB) <= 0) {
		return '';
	}

	// On construit une chaine comprenant toutes périodes de la réservation.
	// Les périodes sont regroupées par date séparées par un -
	// Pour chaque date, on a le codage date + timetableID + timetableLineIDList
	// timetableLineIDList est la liste des timetableLineID séparés par un *
	$premier = true;

	foreach ($bookingLinesDB as $bookingLine) {
		if ($premier) {
			$url = $bookingLine['date']->format('Ymd').'+'.$bookingLine['timetableID'].'+'.$bookingLine['timetableLineID'];
		
		} else if ($bookingLine['date']->format('Ymd') != $memo_date) {
			$url .= '-'.$bookingLine['date']->format('Ymd').'+'.$bookingLine['timetableID'].'+'.$bookingLine['timetableLineID'];

		} else {
			$url .= '*'.$bookingLine['timetableLineID'];
		}

		$premier = false;
		$memo_date = $bookingLine['date']->format('Ymd');
	}
	return $url;
	}


	// Retourne une chaine correspondant à la liste des utilisateurs d'une réservation
	static function getBookingUsersUrl($em, \SD\CoreBundle\Entity\Booking $booking)
	{
	$buRepository = $em->getRepository('SDCoreBundle:BookingUser');
	$bookingUsersDB = $buRepository->getBookingUsers($booking);
	if (count($bookingUsersDB) <= 0) {
		return '';
	}

	$premier = true;

	foreach ($bookingUsersDB as $bookingUser) {
		if ($premier) {
			$url = $bookingUser['userFileID'];
		} else {
			$url .= '-'.$bookingUser['userFileID'];
		}
		$premier = false;
	}
	return $url;
	}


	// Retourne un tableau des utilisateurs d'une réservation
	static function getBookingUsersArray($em, \SD\CoreBundle\Entity\Booking $booking, \SD\CoreBundle\Entity\UserFile $currentUserFile)
	{
	$buRepository = $em->getRepository('SDCoreBundle:BookingUser');

	$bookingUsers = $buRepository->findBy(array('booking' => $booking), array('id' => 'asc'));

	$userFiles = array();

	if (count($bookingUsers) <= 0) { // Ce cas ne doit pas arriver. Toute réservation a au moins un utilisateur. Mais si cela arrive, on initialise la liste des utilisateurs avec l'utilisateur courant
		$userFiles[] = $currentUserFile;
		return $userFiles;
	}

	foreach ($bookingUsers as $bookingUser) {
		$userFiles[] = $bookingUser->getUserFile();
	}
	return $userFiles;
	}
}

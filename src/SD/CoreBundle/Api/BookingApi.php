<?php
// src/SD/CoreBundle/Api/BookingApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\Resource;
use SD\CoreBundle\Entity\TimetableLine;
use SD\CoreBundle\Entity\BookingDateNDB;
use SD\CoreBundle\Entity\BookingPeriodNDB;
use SD\CoreBundle\Entity\BookingNDB;
use SD\CoreBundle\Entity\SelectedEntity;
use SD\CoreBundle\Entity\AddEntity;
use SD\CoreBundle\Entity\Constants;

use SD\CoreBundle\Api\ResourceApi;

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
    

	// Gestion des utilisateurs des réservations

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
			$userFile = new SelectedEntity(); // classe générique des entités sélectionnées
			$userFile->setId($userFileDB->getId());
			$userFile->setName($userFileDB->getFirstAndLastName());
			$userFile->setImageName($userFileDB->getAdministrator() ? "administrator-32.png" : "user-32.png");
			$userFileIDArray_tprr = $userFileIDArray;
			unset($userFileIDArray_tprr[$i]);
			$userFile->setEntityIDList_unselect(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur désélectionne l'utilisateur
			if (count($userFileIDArray) > 1) {
				if ($i > 0) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i-1];
					$userFileIDArray_tprr[$i-1] = $userFileID;
					$userFile->setEntityIDList_sortBefore(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur remonte l'utilisateur dans l'ordre de tri
				}
				if ($i < count($userFileIDArray)-1) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i+1];
					$userFileIDArray_tprr[$i+1] = $userFileID;
					$userFile->setEntityIDList_sortAfter(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur redescend l'utilisateur dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedUserFiles, $userFile);
		}
	}
	return $selectedUserFiles;
    }

	// Retourne un tableau des utilisateurs pouvant être ajoutés à une réservation
	static function getAvailableUserFiles($userFilesDB, $selectedUserFileIDList)
    {
	$selectedUserFileIDArray = explode('-', $selectedUserFileIDList);
	$availableUserFiles = array();
    foreach ($userFilesDB as $userFileDB) {
		if (array_search($userFileDB->getId(), $selectedUserFileIDArray) === false) {
			$userFile = new AddEntity(); // classe générique des entités pouvant être ajoutées à la sélection
			$userFile->setId($userFileDB->getId());
			$userFile->setName($userFileDB->getFirstAndLastName());
			$userFile->setImageName($userFileDB->getAdministrator() ? "administrator-32.png" : "user-32.png");
			$userFile->setEntityIDList_select(($selectedUserFileIDList == '') ? $userFileDB->getId() : ($selectedUserFileIDList.'-'.$userFileDB->getId())); // Liste des utilisateurs sélectionnés si l'utilisateur sélectionne l'utilisateur
			array_push($availableUserFiles, $userFile);
		}
	}
	return $availableUserFiles;
    }

	// Retourne un tableau d'utilisateurs à partir d'une liste d'ID
	static function getUserFiles($em, $userFileIDList)
	{
	$userFileIDArray = explode("-", $userFileIDList);
	$userFiles = array();
	$ufRepository = $em->getRepository('SDCoreBundle:UserFile');
	foreach ($userFileIDArray as $userFileID) {
		$userFile = $ufRepository->find($userFileID);
		if ($userFile !== null) {
			$userFiles[] = $userFile;
		}
	}
	return $userFiles;
	}

	// Retourne un tableau des ressources à planifier (initialisation de planification)
	static function initAvailableUserFiles($em, \SD\CoreBundle\Entity\File $file, $selectedUserFileIDList)
	{
	$ufRepository = $em->getRepository('SDCoreBundle:UserFile');

	$userFilesDB = $ufRepository->getUserFiles($file);
	return BookingApi::getAvailableUserFiles($userFilesDB, $selectedUserFileIDList);
	}

	// Gestion des étiquettes des réservations

	// Retourne un tableau des étiquettes sélectionnées
	// labelIDList: Liste des ID des étiquettes sélectionnées
	static function getSelectedLabels($em, $labelIDList)
	{
	$l_labelIDList = ($labelIDList == '0') ? '' : $labelIDList; // On ramène la chaine '0' à une chaine vide

	$labelIDArray = explode('-', $l_labelIDList);
    $lRepository = $em->getRepository('SDCoreBundle:Label');
	$selectedLabels = array();
	$i = 0;
    foreach ($labelIDArray as $labelID) {
		$labelDB = $lRepository->find($labelID);
		if ($labelDB !== null) {
			$label = new SelectedEntity(); // classe générique des entités sélectionnées
			$label->setId($labelDB->getId());
			$label->setName($labelDB->getName());
			$label->setImageName("label-32.png");
			$labelIDArray_tprr = $labelIDArray;
			unset($labelIDArray_tprr[$i]);
			$label->setEntityIDList_unselect((count($labelIDArray_tprr) > 0) ? implode('-', $labelIDArray_tprr) : '0'); // Liste des étiquettes sélectionnées si l'utilisateur désélectionne l'étiquette
			if (count($labelIDArray) > 1) {
				if ($i > 0) {
					$labelIDArray_tprr = $labelIDArray;
					$labelIDArray_tprr[$i] = $labelIDArray_tprr[$i-1];
					$labelIDArray_tprr[$i-1] = $labelID;
					$label->setEntityIDList_sortBefore((count($labelIDArray_tprr) > 0) ? implode('-', $labelIDArray_tprr) : '0'); // Liste des étiquettes sélectionnées si l'utilisateur remonte l'étiquette dans l'ordre de tri
				}
				if ($i < count($labelIDArray)-1) {
					$labelIDArray_tprr = $labelIDArray;
					$labelIDArray_tprr[$i] = $labelIDArray_tprr[$i+1];
					$labelIDArray_tprr[$i+1] = $labelID;
					$label->setEntityIDList_sortAfter((count($labelIDArray_tprr) > 0) ? implode('-', $labelIDArray_tprr) : '0'); // Liste des étiquettes sélectionnées si l'utilisateur redescend l'étiquette dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedLabels, $label);
		}
	}
	return $selectedLabels;
    }
	
	// Retourne un tableau des étiquettes pouvant être ajoutées à une réservation
	static function getAvailableLabels($labelsDB, $selectedLabelIDList)
    {
	$l_selectedLabelIDList = ($selectedLabelIDList == '0') ? '' : $selectedLabelIDList; // On ramène la chaine '0' à une chaine vide

	$selectedLabelIDArray = explode('-', $l_selectedLabelIDList);
	$availableLabels = array();
    foreach ($labelsDB as $labelDB) {
		if (array_search($labelDB->getId(), $selectedLabelIDArray) === false) {
			$label = new AddEntity(); // classe générique des entités pouvant être ajoutées à la sélection
			$label->setId($labelDB->getId());
			$label->setName($labelDB->getName());
			$label->setImageName("label-32.png");
			$label->setEntityIDList_select(($l_selectedLabelIDList == '') ? $labelDB->getId() : ($l_selectedLabelIDList.'-'.$labelDB->getId())); // Liste des étiquettes sélectionnées si l'utilisateur sélectionne l'étiquette
			array_push($availableLabels, $label);
		}
	}
	return $availableLabels;
    }

	// Retourne un tableau des étiquettes pouvant être ajoutées à une réservation
	static function initAvailableLabels($em, \SD\CoreBundle\Entity\File $file, $selectedLabelIDList)
	{
	$lRepository = $em->getRepository('SDCoreBundle:Label');
	$labelsDB = $lRepository->getLabels	($file);
	return BookingApi::getAvailableLabels($labelsDB, $selectedLabelIDList);
	}

	// Retourne une chaine correspondant à la liste des étiquettes d'une réservation
	static function getBookingLabelsUrl($em, \SD\CoreBundle\Entity\Booking $booking)
	{
	$blRepository = $em->getRepository('SDCoreBundle:BookingLabel');
	$bookingLabelsDB = $blRepository->getBookingLabels($booking);
	if (count($bookingLabelsDB) <= 0) {
		return '0';
	}
	$premier = true;
	foreach ($bookingLabelsDB as $bookingLabel) {
		if ($premier) {
			$url = $bookingLabel['labelID'];
		} else {
			$url .= '-'.$bookingLabel['labelID'];
		}
		$premier = false;
	}
	return $url;
	}

	// ATTENTION À CETTE PROCEDURE. C'EST FAUX
	// Retourne un tableau des utilisateurs d'une réservation
	static function getBookingLabelsArray($em, \SD\CoreBundle\Entity\Booking $booking, \SD\CoreBundle\Entity\UserFile $currentUserFile)
	{
	$blRepository = $em->getRepository('SDCoreBundle:BookingLabel');
	$bookingLabels = $blRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	$userFiles = array();
	if (count($bookingLabels) <= 0) { // Ce cas ne doit pas arriver. Toute réservation a au moins un utilisateur. Mais si cela arrive, on initialise la liste des utilisateurs avec l'utilisateur courant
		$userFiles[] = $currentUserFile;
		return $userFiles;
	}
	foreach ($bookingLabels as $bookingLabel) {
		$userFiles[] = $bookingLabel->getUserFile();
	}
	return $userFiles;
	}


	// Retourne un tableau d'étiquettes à partir d'une liste d'ID
	static function getLabels($em, $labelIDList)
	{
	$labelIDArray = explode("-", $labelIDList);
	$labels = array();
	$lRepository = $em->getRepository('SDCoreBundle:Label');
	foreach ($labelIDArray as $labelID) {
		if ($labelID > 0) { // Le labelID peut être égal à 0: cas d'une liste vide
			$label = $lRepository->find($labelID);
			if ($label !== null) {
				$labels[] = $label;
			}
		}
	}
	return $labels;
	}

	// Retourne un tableau d'identifiants d'étiquettes à partir d'une liste d'ID
	static function getLabelsID($labelIDList)
	{
	$labelsID = array();
	if (strcmp($labelIDList, "0") != 0) {
		$labelsID = explode("-", $labelIDList);
	}
	return $labelsID;
	}

	// Retourne les informations de début et de fin de réservation à partir d'une liste de périodes contenue dans une Url
    static function getBookingLinesUrlBeginningAndEndPeriod($em, $timetableLinesList, &$beginningDate, &$beginningTimetableLine, &$endDate, &$endTimetableLine)
	{
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


	static function getTimetableBookings($em, \SD\CoreBundle\Entity\File $file, \Datetime $date, \SD\CoreBundle\Entity\Planification $planification, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod, \SD\CoreBundle\Entity\UserFile $currentUserFile)
	{
	$bRepository = $em->getRepository('SDCoreBundle:Booking');
	$buRepository = $em->getRepository('SDCoreBundle:BookingUser');

	$bookingsDB = $bRepository->getTimetableBookings($file, $date, $planification, $planificationPeriod);
	$bookings = array();

	if (count($bookingsDB) <= 0) {
		return $bookings;
	}

	$evenResourcesID = ResourceApi::getEvenPlanifiedResourcesID($em, $planificationPeriod);

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
}

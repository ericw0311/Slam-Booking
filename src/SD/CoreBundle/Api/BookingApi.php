<?php
// src/SD/CoreBundle/Api/BookingApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\PlanificationPeriod;
use SD\CoreBundle\Entity\TimetableLine;
use SD\CoreBundle\Entity\BookingDateNDB;
use SD\CoreBundle\Entity\BookingPeriodNDB;
use SD\CoreBundle\Entity\Constants;

class BookingApi
{
	static function getEndPeriods($em, PlanificationPeriod $planificationPeriod, \Datetime $beginningDate, TimetableLine $beginningTimetableLine)
	{
	$planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');
	$endPeriods = array();
	$dateIndex = 0;
	$numberPeriods = 0;
	$numberDates = 0;
	$continue = true;

	while ($continue) {
		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$dateIndex.'D'));
		
		$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));
		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			
			$numberDates++;
			$endDate = new BookingDateNDB($date);

			$timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());
			$dateTimetableLinesList = $date->format('Ymd').'+'.$planificationLine->getTimetable()->getID();

			$firstDatePeriod = true; // Premiere periode de la date

			foreach ($timetableLines as $key => $timetableLine) {

				// Période inférieure à la période de début de réservation
				$beforeFirstPeriod = ($dateIndex <= 0 && $timetableLine->getBeginningTime() < $beginningTimetableLine->getBeginningTime());

				// On a atteint le nombre maximum de périodes d'une réservation
				$afterLastPeriod = ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES);

				$status = "D";
				if ($beforeFirstPeriod || $afterLastPeriod) { $status = "N"; }

				if ($status == "D") {
	$dateTimetableLinesList = ($firstDatePeriod) ? ($dateTimetableLinesList.'+'.$timetableLine->getID()) : ($dateTimetableLinesList.'*'.$timetableLine->getID());
				}

				$periodTimetableLinesList = ($numberDates <= 1) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);

				$endPeriod = new BookingPeriodNDB($timetableLine, $periodTimetableLinesList, $status);
				$endDate->addPeriod($endPeriod);
				if ($status == "D") { $firstDatePeriod = false; }
				
				if ($status == "D" && $numberPeriods < Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $numberPeriods++; }
				if ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $continue = false; } // Nombre maximum de periodes pour une reservation atteint
			}
			$endPeriods[] = $endDate;

			$timetableLinesList = ($dateIndex <= 0) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);
		}
		$dateIndex++;
	}
	return $endPeriods;
    }
}

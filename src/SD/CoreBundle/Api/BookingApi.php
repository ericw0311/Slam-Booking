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

	while ($numberPeriods < Constants::MAXIMUM_NUMBER_BOOKING_LINES) {
		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$dateIndex.'D'));
		
		$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));
		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			
			$endDate = new BookingDateNDB($date);

			$timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());
			$dateTimetableLinesList = $date->format('Ymd').'-'.$planificationLine->getTimetable()->getID();

			foreach ($timetableLines as $key => $timetableLine) {

				$status = "D";
				if ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES || // On a atteint le nombre maximum de périodes d'une réservation.
					($dateIndex <= 0 && $timetableLine->getBeginningTime() < $beginningTimetableLine->getBeginningTime())) // On est sur une période inférieure à la période de début de réservation
					{ $status = "N"; }

				$dateTimetableLinesList = ($key <= 0) ? ($dateTimetableLinesList.'-'.$timetableLine->getID()) : ($dateTimetableLinesList.'*'.$timetableLine->getID());

				$endPeriod = new BookingPeriodNDB($timetableLine, ($dateIndex <= 0) ? $dateTimetableLinesList : ($timetableLinesList.'+'.$dateTimetableLinesList), $status);
				$endDate->addPeriod($endPeriod);
				
				if ($status == "D" && $numberPeriods < Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $numberPeriods++; }
			}
			$endPeriods[] = $endDate;

			$timetableLinesList = ($dateIndex <= 0) ? $dateTimetableLinesList : ($timetableLinesList.'+'.$dateTimetableLinesList);
		}
		$dateIndex++;
	}
	return $endPeriods;
    }
}

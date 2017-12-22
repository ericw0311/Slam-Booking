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
	$i = 0;
	$numberPeriods = 0;

	while ($numberPeriods < Constants::MAXIMUM_NUMBER_BOOKING_LINES) {
		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$i.'D'));
		
		$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));
		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			
			$endDate = new BookingDateNDB($date);

			$timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());

			foreach ($timetableLines as $timetableLine) {

				$status = "D";
				if ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES || // On a atteint le nombre maximum de périodes d'une réservation.
					($i <= 0 && $timetableLine->getBeginningTime() < $beginningTimetableLine->getBeginningTime())) // On est sur une période inférieure à la période de début de réservation
					{ $status = "N"; }

				$endPeriod = new BookingPeriodNDB($timetableLine, $status);
				$endDate->addPeriod($endPeriod);
				
				if ($status == "D" && $numberPeriods < Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $numberPeriods++; }
			}
			$endPeriods[] = $endDate;
		}
		$i++;
	}
	return $endPeriods;
    }
}

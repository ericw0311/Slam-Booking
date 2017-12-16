<?php
// src/SD/CoreBundle/Api/BookingApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\BookingPeriodNDB;
use SD\CoreBundle\Entity\Constants;

class BookingApi
{
	// Retourne un tableau des périodes de fin de réservation possibles
	static function getEndPeriods($em, PlanificationPeriod $planificationPeriod, \Datetime $beginningDate, Timetable $beginningTimetable, TimetableLine $beginningTimetableLine)
	{
	$planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
	$timetableLineRepository = $em->getRepository('SDCoreBundle:TimetableLine');

	$endPeriods = array();
	$i = 0;
	$continue = true;

	while ($continue) {

		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$i.'D'));
		
		$planificationLine = $planificationLineRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));

		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			
			$timetableLines = $timetableLineRepository->getTimetableLines($planificationLine->getTimetable());

		}

	}
	return $endPeriods;
    }
}

<?php
// src/SD/CoreBundle/Event/PlanificationPeriodEvent.php
namespace SD\CoreBundle\Event;

use SD\CoreBundle\Entity\PlanificationLine;
use SD\CoreBundle\Entity\Constants;

class PlanificationPeriodEvent
{
    static function postPersist($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
	PlanificationPeriodEvent::createPlanificationLine($em, $user, $planificationPeriod);
    }

    // Initialise les lignes de planification
    static function createPlanificationLine($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
	foreach (Constants::WEEK_DAY_CODE as $dayOrder => $dayCode) {
		$planificationLine = new PlanificationLine($user, $planificationPeriod, $dayCode, $dayOrder);
		$em->persist($planificationLine);
	}
    $em->flush();
    }
}

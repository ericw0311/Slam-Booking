<?php
// src/SD/CoreBundle/Api/PlanningApi.php
namespace SD\CoreBundle\Api;

use SD\CoreBundle\Entity\Planification;
use SD\CoreBundle\Entity\UserParameter;

class PlanningApi
{
    // Retourne la planification en cours d'un utilisateur
    static function getCurrentCalendarPlanification($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    return $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.planification'));
    }


    // Retourne l'ID de la planification en cours d'un utilisateur
    static function getCurrentCalendarPlanificationID($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    $userParameter = $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.planification'));
    if ($userParameter === null) {
        return 0;
    } else {
        return $userParameter->getIntegerValue();
    }
    }

    // Positionne la planification comme planification en cours
    static function setCurrentCalendarPlanification($em, \SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Planification $planification)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarPlanification($em, $user);

    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.planification');
        $userParameter->setSDIntegerValue($planification->getId());
        $em->persist($userParameter);
	} else {
        $userParameter->setSDIntegerValue($planification->getId());
	}
	$em->flush();
    }

    // Positionne la planification comme planification en cours (idem setCurrentCalendarPlanification mais directement à partir de l'ID de la planification)
    static function setCurrentCalendarPlanificationID($em, \SD\UserBundle\Entity\User $user, $planificationID)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarPlanification($em, $user);
    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.planification');
        $userParameter->setSDIntegerValue($planificationID);
        $em->persist($userParameter);
    } else {
        $userParameter->setSDIntegerValue($planificationID);
    }
    $em->flush();
	}
	
	
    // Retourne la planification en cours d'un utilisateur
    static function getCurrentCalendarMany($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    return $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.many'));
    }

    // Retourne l'indicateur many (affichage du planning) d'un utilisateur
    static function getCurrentCalendarManyValue($em, \SD\UserBundle\Entity\User $user)
    {
    $userParameterRepository = $em->getRepository('SDCoreBundle:UserParameter');
    $userParameter = $userParameterRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.many'));
    if ($userParameter === null) {
        return false;
    } else {
        return $userParameter->getBooleanValue();
    }
    }

    // Positionne l'indicateur many de l'affichage du calendrier
    static function setCurrentCalendarMany($em, \SD\UserBundle\Entity\User $user, $many)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarMany($em, $user);

    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.many');
		$userParameter->setSDBooleanValue(($many > 0));
        $em->persist($userParameter);
	} else {
		$userParameter->setSDBooleanValue(($many > 0));
	}
	$em->flush();
    }
}

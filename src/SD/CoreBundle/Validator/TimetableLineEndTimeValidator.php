<?php
// src/SD/CoreBundle/Validator/TimetableLineEndTimeValidator.php

namespace SD\CoreBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TimetableLineEndTimeValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
    $this->em = $em;
    }

    public function getEntityManager()
    {
    return $this->em;
    }

    public function validate($timetableLine, Constraint $constraint)
    {
    $entityManager = $this->getEntityManager();
    $timetableRepository = $entityManager->getRepository('SDCoreBundle:Timetable');
    $timetable = $timetableRepository->find($timetableLine->getTimetable()->getID());
	$timetableLineRepository = $entityManager->getRepository('SDCoreBundle:TimetableLine');

	if ($timetableLine->getId() > 0) { // On est en mise a jour de créneau horaire
		$nextTimetableLine = $timetableLineRepository->getNextTimetableLine($timetable, $timetableLine->getId());

		if ($nextTimetableLine != null) { // Il existe un créneau suivant
			$interval = date_diff($timetableLine->getEndTime(), $nextTimetableLine->getBeginningTime());
			if ($interval->format("%R") == "-") { // L'heure de fin est supérieure à l'heure de début du créneau suivant
			$this->context->buildViolation($constraint->message)
				->setParameter('%beginningTime%', date_format($nextTimetableLine->getBeginningTime(), "H:i"))
				->addViolation();
			}
		}
	}	
    }
}

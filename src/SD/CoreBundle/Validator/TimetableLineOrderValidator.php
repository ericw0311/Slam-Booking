<?php
// src/SD/CoreBundle/Validator/TimetableLineOrder.php

namespace SD\CoreBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TimetableLineOrderValidator extends ConstraintValidator
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
	$previousTimetableLine = null;
	$nextTimetableLine = null;

	if ($timetableLine->getId() > 0) { // On est en mise a jour de créneau horaire
		$previousTimetableLine = $timetableLineRepository->getPreviousTimetableLine($timetable, $timetableLine->getId());
	} else { // On est en création de créneau horaire
		$previousTimetableLine = $timetableLineRepository->getLastTimetableLine($timetable);
	}

	if ($previousTimetableLine != null) { // Il existe un créneau précédent
		$interval = date_diff($previousTimetableLine->getEndTime(), $timetableLine->getBeginningTime());

		if ($interval->format("%R") == "-") { // L'heure de début est inférieure à l'heure de fin du créneau précédent
			$this->context->buildViolation($constraint->message)
				->setParameter('%endTime%', date_format($previousTimetableLine->getEndTime(), "H:i"))
				->addViolation();
		}
	}
    }
}

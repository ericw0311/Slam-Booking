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

    $timetableHeaderRepository = $entityManager->getRepository('SDCoreBundle:TimetableHeader');
    $timetableHeader = $timetableHeaderRepository->find($timetableLine->getTimetableHeader()->getID());

	$timetableLineRepository = $entityManager->getRepository('SDCoreBundle:TimetableLine');
	$previousTimetableLine = null;
	if ($timetableLine->getId() > 0) {
		$previousTimetableLine = $timetableLineRepository->getPreviousTimetableLine($timetableHeader, $timetableLine->getId());
	}

	$interval = date_diff($previousTimetableLine->getBeginningTime(), $timetableLine->getBeginningTime());

	if ($previousTimetableLine != null) { // Le créneau précédent est trouvé 
		// C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message de la contrainte
		$this->context->buildViolation($constraint->message)
//			->setParameter('%beginningTime%', date_format($previousTimetableLine->getBeginningTime(), "H:i"))
			->setParameter('%beginningTime%', $interval->format('%R%h%i minuts'))
            ->addViolation();
    }
    }
}

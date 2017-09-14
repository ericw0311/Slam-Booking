<?php

namespace SD\CoreBundle\Entity;

class TimetableContext
{
    protected $planificationPeriodsCount;

    public function setPlanificationPeriodsCount($planificationPeriodsCount)
    {
    $this->planificationPeriodsCount = $planificationPeriodsCount;
    return $this;
    }

    public function getPlanificationPeriodsCount()
    {
    return $this->planificationPeriodsCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\Timetable $timetable)
    {
    $planificationLineRepository = $em->getRepository('SDCoreBundle:PlanificationLine');
    $this->setPlanificationPeriodsCount($planificationLineRepository->getPlanificationPeriodsCount($timetable));

    return $this;
    }
}

<?php

namespace SD\CoreBundle\Entity;

class PlanificationContext
{
    protected $previousPlanificationPeriod;
    protected $nextPlanificationPeriod;

    public function setPreviousPlanificationPeriod($previousPlanificationPeriod)
    {
    $this->previousPlanificationPeriod = $previousPlanificationPeriod;
    return $this;
    }

    public function getPreviousPlanificationPeriod()
    {
    return $this->previousPlanificationPeriod;
    }

    public function getPreviousPP()
    {
    return ($this->previousPlanificationPeriod !== null);
    }

    public function setNextPlanificationPeriod($nextPlanificationPeriod)
    {
    $this->nextPlanificationPeriod = $nextPlanificationPeriod;
    return $this;
    }

    public function getNextPlanificationPeriod()
    {
    return $this->nextPlanificationPeriod;
    }

    public function getNextPP()
    {
    return ($this->nextPlanificationPeriod !== null);
    }

    function __construct($em, \SD\CoreBundle\Entity\Planification $planification, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
    $planificationPeriodRepository = $em->getRepository('SDCoreBundle:PlanificationPeriod');
    $this->setPreviousPlanificationPeriod($planificationPeriodRepository->getPreviousPlanificationPeriod($planification, $planificationPeriod->getID()));

    $this->setNextPlanificationPeriod($planificationPeriodRepository->getNextPlanificationPeriod($planification, $planificationPeriod->getID()));
    return $this;
    }
}

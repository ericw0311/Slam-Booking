<?php

namespace SD\CoreBundle\Entity;

class ResourceContext
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

    function __construct($em, \SD\CoreBundle\Entity\Resource $resource)
    {
    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $this->setPlanificationPeriodsCount($planificationResourceRepository->getPlanificationPeriodsCount($resource));

    return $this;
    }
}

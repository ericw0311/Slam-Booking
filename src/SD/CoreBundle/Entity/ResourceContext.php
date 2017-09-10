<?php

namespace SD\CoreBundle\Entity;

class ResourceContext
{
    protected $planificationCount;

    public function setPlanificationCount($planificationCount)
    {
    $this->planificationCount = $planificationCount;
    return $this;
    }

    public function getPlanificationCount()
    {
    return $this->planificationCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\Resource $resource)
    {
    $planificationResourceRepository = $em->getRepository('SDCoreBundle:PlanificationResource');
    $this->setPlanificationCount($planificationResourceRepository->getPlanificationPeriodsCount($resource));

    return $this;
    }
}

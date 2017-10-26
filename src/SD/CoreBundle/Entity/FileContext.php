<?php

namespace SD\CoreBundle\Entity;

class FileContext
{
    protected $userFileCount = 0;
    protected $timetableCount = 0;
    protected $labelCount = 0;
    protected $activityCount = 0;
    protected $resourceCount = 0;
    protected $planificationCount = 0;

    public function setUserFileCount($userFileCount)
    {
    $this->userFileCount = $userFileCount;
    return $this;
    }

    public function getUserFileCount()
    {
    return $this->userFileCount;
    }

    public function setTimetableCount($timetableCount)
    {
    $this->timetableCount = $timetableCount;
    return $this;
    }

    public function getTimetableCount()
    {
    return $this->timetableCount;
    }

    public function setLabelCount($labelCount)
    {
    $this->labelCount = $labelCount;
    return $this;
    }

    public function getLabelCount()
    {
    return $this->labelCount;
    }

    public function setActivityCount($activityCount)
    {
    $this->activityCount = $activityCount;
    return $this;
    }

    public function getActivityCount()
    {
    return $this->activityCount;
    }

    public function setResourceCount($resourceCount)
    {
    $this->resourceCount = $resourceCount;
    return $this;
    }

    public function getResourceCount()
    {
    return $this->resourceCount;
    }

    public function setPlanificationCount($planificationCount)
    {
    $this->planificationCount = $planificationCount;
    return $this;
    }

    public function getPlanificationCount()
    {
    return $this->planificationCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\File $file)
    {
    $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
    $this->setUserFileCount($userFileRepository->getUserFilesCount($file));

    $labelRepository = $em->getRepository('SDCoreBundle:Label');
    $this->setLabelCount($labelRepository->getLabelsCount($file));

    $timetableRepository = $em->getRepository('SDCoreBundle:Timetable');
    $this->setTimetableCount($timetableRepository->getTimetablesCount($file));

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $this->setResourceCount($resourceRepository->getResourcesCount($file));

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');
    $this->setPlanificationCount($planificationRepository->getPlanificationsCount($file));

    return $this;
    }
}

<?php

namespace SD\CoreBundle\Entity;

class FileContext
{
    protected $userFileCount;
    protected $timetableCount;
    protected $activityCount;
    protected $resourceCount;
    protected $planificationCount;

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

    $activityRepository = $em->getRepository('SDCoreBundle:Activity');
    $this->setActivityCount($activityRepository->getActivitiesCount($file));

    $timetableRepository = $em->getRepository('SDCoreBundle:Timetable');
    $this->setTimetableCount($timetableRepository->getTimetablesCount($file));

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $this->setResourceCount($resourceRepository->getResourcesCount($file));

    $planificationRepository = $em->getRepository('SDCoreBundle:Planification');
    $this->setPlanificationCount($planificationRepository->getPlanificationsCount($file));

    return $this;
    }
}

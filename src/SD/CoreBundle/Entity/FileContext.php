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
	protected $allBookingsCount = 0;

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

    public function setAllBookingsCount($bookingsCount)
    {
    $this->allBookingsCount = $bookingsCount;
    return $this;
    }

    public function getAllBookingsCount()
    {
    return $this->allBookingsCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\File $file)
    {
    $ufRepository = $em->getRepository('SDCoreBundle:UserFile');
    $this->setUserFileCount($ufRepository->getUserFilesCount($file));

    $lRepository = $em->getRepository('SDCoreBundle:Label');
    $this->setLabelCount($lRepository->getLabelsCount($file));

    $tRepository = $em->getRepository('SDCoreBundle:Timetable');
    $this->setTimetableCount($tRepository->getTimetablesCount($file));

    $rRepository = $em->getRepository('SDCoreBundle:Resource');
    $this->setResourceCount($rRepository->getResourcesCount($file));

    $pRepository = $em->getRepository('SDCoreBundle:Planification');
    $this->setPlanificationCount($pRepository->getPlanificationsCount($file));

    $bRepository = $em->getRepository('SDCoreBundle:Booking');
    $this->setAllBookingsCount($bRepository->getAllBookingsCount($file));

    return $this;
    }
}

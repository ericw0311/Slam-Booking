<?php

namespace SD\CoreBundle\Entity;

class FileContext
{
    protected $userFileCount;
    protected $timetableCount;
    protected $activityCount;

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

    function __construct($em, \SD\CoreBundle\Entity\File $file)
    {
    $userFileRepository = $em->getRepository('SDCoreBundle:UserFile');
    $this->setUserFileCount($userFileRepository->getUserFilesCount($file));

    return $this;
    }
}

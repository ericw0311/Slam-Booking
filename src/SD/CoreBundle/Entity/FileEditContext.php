<?php

namespace SD\CoreBundle\Entity;

class FileEditContext
{
    protected $userTimetablesCount; // Nombre de grilles horaires saisies par l'utilisateur (type = T)
    protected $labelsCount;
    protected $resourcesCount;

    public function setUserTimetablesCount($timetablesCount)
    {
    $this->userTimetablesCount = $timetablesCount;
    return $this;
    }

    public function getUserTimetablesCount()
    {
    return $this->userTimetablesCount;
    }

    public function setLabelsCount($labelsCount)
    {
    $this->labelsCount = $labelsCount;
    return $this;
    }

    public function getLabelsCount()
    {
    return $this->labelsCount;
    }

    public function setResourcesCount($resourcesCount)
    {
    $this->resourcesCount = $resourcesCount;
    return $this;
    }

    public function getResourcesCount()
    {
    return $this->resourcesCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\File $file)
    {
    $labelRepository = $em->getRepository('SDCoreBundle:Label');
    $this->setLabelsCount($labelRepository->getLabelsCount($file));

    $timetableRepository = $em->getRepository('SDCoreBundle:Timetable');
    $this->setUserTimetablesCount($timetableRepository->getUserTimetablesCount($file));

    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $this->setResourcesCount($resourceRepository->getResourcesCount($file));

    return $this;
    }
}

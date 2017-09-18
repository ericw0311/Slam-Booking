<?php

namespace SD\CoreBundle\Entity;

class ResourceClassificationContext
{
    protected $resourcesCount;

    public function setResourcesCount($resourcesCount)
    {
    $this->resourcesCount = $resourcesCount;
    return $this;
    }

    public function getResourcesCount()
    {
    return $this->resourcesCount;
    }

    function __construct($em, \SD\CoreBundle\Entity\ResourceClassification $resourceClassification)
    {
    $resourceRepository = $em->getRepository('SDCoreBundle:Resource');
    $this->setResourcesCount($resourceRepository->getResourcesCount_RC($resourceClassification));

    return $this;
    }
}

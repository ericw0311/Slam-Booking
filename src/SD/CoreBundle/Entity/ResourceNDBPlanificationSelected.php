<?php

namespace SD\CoreBundle\Entity;

// NDB = not database
class ResourceNDBPlanificationSelected
{
    private $id = 0;
    private $name;
    private $internal;
    private $type;
    private $code;
    private $resourceIDList_sortAfter;
    private $resourceIDList_sortBefore;
    private $resourceIDList_unselect;

    public function setId($id)
    {
		$this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setInternal($internal)
    {
		$this->internal = $internal;
        return $this;
    }

    public function getInternal()
    {
		return $this->internal;
    }

    public function setType($type)
    {
		$this->type = $type;
        return $this;
    }

    public function getType()
    {
		return $this->type;
    }

    public function setCode($code)
    {
		$this->code = $code;
        return $this;
    }

    public function getCode()
    {
		return $this->code;
    }

    public function setResourceIDList_sortAfter($resourceIDList)
    {
        $this->resourceIDList_sortAfter = $resourceIDList;
        return $this;
    }

    public function getResourceIDList_sortAfter()
    {
        return $this->resourceIDList_sortAfter;
    }

    public function setResourceIDList_sortBefore($resourceIDList)
    {
        $this->resourceIDList_sortBefore = $resourceIDList;
        return $this;
    }

    public function getResourceIDList_sortBefore()
    {
        return $this->resourceIDList_sortBefore;
    }

    public function setResourceIDList_unselect($resourceIDList)
    {
        $this->resourceIDList_unselect = $resourceIDList;
        return $this;
    }

    public function getResourceIDList_unselect()
    {
        return $this->resourceIDList_unselect;
    }
}

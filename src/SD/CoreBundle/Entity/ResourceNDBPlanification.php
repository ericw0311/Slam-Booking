<?php

namespace SD\CoreBundle\Entity;

// NDB = not database
class ResourceNDBPlanification
{
    private $id = 0;
    private $name;
    private $internal;
    private $type;
    private $code;
    private $resourceIDList;

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

    public function setResourceIDList($resourceIDList)
    {
        $this->resourceIDList = $resourceIDList;
        return $this;
    }

    public function getResourceIDList()
    {
        return $this->resourceIDList;
    }
}

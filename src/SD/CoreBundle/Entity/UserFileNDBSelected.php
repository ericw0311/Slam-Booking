<?php
namespace SD\CoreBundle\Entity;

// NDB = not database
class UserFileNDBSelected
{
    private $id = 0;
    private $lastName;
    private $firstName;
    private $administrator;
    private $userFileIDList_sortAfter;
    private $userFileIDList_sortBefore;
    private $userFileIDList_unselect;

    public function setId($id)
    {
		$this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getFirstAndLastName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

	public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
        return $this;
    }

    public function getAdministrator()
    {
        return $this->administrator;
    }

    public function setUserFileIDList_sortAfter($userFileIDList)
    {
        $this->userFileIDList_sortAfter = $userFileIDList;
        return $this;
    }
    public function getUserFileIDList_sortAfter()
    {
        return $this->userFileIDList_sortAfter;
    }
    public function setUserFileIDList_sortBefore($userFileIDList)
    {
        $this->userFileIDList_sortBefore = $userFileIDList;
        return $this;
    }
    public function getUserFileIDList_sortBefore()
    {
        return $this->userFileIDList_sortBefore;
    }
    public function setUserFileIDList_unselect($userFileIDList)
    {
        $this->userFileIDList_unselect = $userFileIDList;
        return $this;
    }
    public function getUserFileIDList_unselect()
    {
        return $this->userFileIDList_unselect;
    }
}

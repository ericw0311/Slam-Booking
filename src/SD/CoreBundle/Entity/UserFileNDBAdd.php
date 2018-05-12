<?php
namespace SD\CoreBundle\Entity;

// NDB = not database
class UserFileNDBAdd
{
    private $id = 0;
    private $lastName;
    private $firstName;
    private $administrator;
    private $userFileIDList_select;

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

	public function setUserFileIDList_select($userFileIDList)
    {
        $this->userFileIDList_select = $userFileIDList;
        return $this;
    }

    public function getUserFileIDList_select()
    {
        return $this->userFileIDList_select;
    }	
}

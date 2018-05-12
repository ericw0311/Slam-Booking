<?php

namespace SD\CoreBundle\Entity;

// NDB = not database

class BookingNDB
{
	private $id;
	private $type;
	private $numberTimetableLines;
	private $cellClass;
	private $userFiles;

	public function setId($id)
	{
	$this->id = $id;
	return $this;
	}

	public function getId()
	{
	return $this->id;
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

	public function setNumberTimetableLines($numberTimetableLines)
	{
	$this->numberTimetableLines = $numberTimetableLines;
	return $this;
	}

	public function getNumberTimetableLines()
	{
	return $this->numberTimetableLines;
	}

	public function setCellClass($cellClass)
	{
	$this->cellClass = $cellClass;
	return $this;
	}

	public function getCellClass()
	{
	return $this->cellClass;
	}

	public function setUserFiles($userFiles)
	{
	$this->userFiles = $userFiles;
	return $this;
	}

	public function getUserFiles()
	{
	return $this->userFiles;
	}

	public function __construct($id, $type, $cellClass)
	{
	$this->setId($id);
	$this->setType($type);
	$this->setNumberTimetableLines(0);
	$this->setCellClass($cellClass);
	$this->setUserFiles(null);
	}
}

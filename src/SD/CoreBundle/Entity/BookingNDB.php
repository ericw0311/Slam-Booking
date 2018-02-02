<?php

namespace SD\CoreBundle\Entity;

// NDB = not database

class BookingNDB
{
	private $id;
	private $firstTimetableLineID;
	private $numberTimetableLines;
	private $cellClass;

	public function setId($id)
	{
	$this->id = $id;
	return $this;
	}

	public function getId()
	{
	return $this->id;
	}

	public function setFirstTimetableLineID($firstTimetableLineID)
	{
	$this->firstTimetableLineID = $firstTimetableLineID;
	return $this;
	}

	public function getFirstTimetableLineID()
	{
	return $this->firstTimetableLineID;
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
	
	public function __construct($id, $firstTimetableLineID, $numberTimetableLines, $cellClass)
	{
	$this->setId($id);
	$this->setFirstTimetableLineID($firstTimetableLineID);
	$this->setNumberTimetableLines($numberTimetableLines);
	$this->setCellClass($cellClass);
	}
}

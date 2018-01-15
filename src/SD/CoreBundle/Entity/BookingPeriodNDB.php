<?php

namespace SD\CoreBundle\Entity;

// NDB = not database
class BookingPeriodNDB
{
	private $timetableLine;
	private $status;
	private $timetableLinesList;

	public function setTimetableLine(\SD\CoreBundle\Entity\TimetableLine $timetableLine)
	{
		$this->timetableLine = $timetableLine;
		return $this;
	}

	public function getTimetableLine()
	{
		return $this->timetableLine;
	}

	public function setTimetableLinesList($timetableLinesList)
	{
		$this->timetableLinesList = $timetableLinesList;
		return $this;
	}

    public function getTimetableLinesList()
    {
        return $this->timetableLinesList;
    }

	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

    public function getStatus()
    {
        return $this->status;
    }

    public function __construct(\SD\CoreBundle\Entity\TimetableLine $timetableLine, $timetableLinesList, $status)
    {
		$this->setTimetableLine($timetableLine);
		$this->setTimetableLinesList($timetableLinesList);
		$this->setStatus($status);
    }
}

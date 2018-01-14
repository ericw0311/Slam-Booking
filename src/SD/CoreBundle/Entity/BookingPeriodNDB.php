<?php

namespace SD\CoreBundle\Entity;

// NDB = not database
class BookingPeriodNDB
{
	private $timetableLine;
	private $status;
	private $url;

	public function setTimetableLine(\SD\CoreBundle\Entity\TimetableLine $timetableLine)
	{
		$this->timetableLine = $timetableLine;
		return $this;
	}

	public function getTimetableLine()
	{
		return $this->timetableLine;
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

	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

    public function getUrl()
    {
        return $this->url;
    }

    public function __construct(\SD\CoreBundle\Entity\TimetableLine $timetableLine, $status)
    {
    $this->setTimetableLine($timetableLine);
    $this->setStatus($status);
    }
}

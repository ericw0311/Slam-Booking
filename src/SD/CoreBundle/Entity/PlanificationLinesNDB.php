<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class PlanificationLinesNDB
{
    private $planificationPeriod;

    private $timetable_MON;
    private $timetable_TUE;
    private $timetable_WED;
    private $timetable_THU;
    private $timetable_FRI;
    private $timetable_SAT;
    private $timetable_SUN;

    public function setPlanificationPeriod(\SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
        $this->planificationPeriod = $planificationPeriod;
        return $this;
    }

    public function getPlanificationPeriod()
    {
        return $this->planificationPeriod;
    }

    public function setTimetableMON(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_MON = $timetable;
        return $this;
    }

    public function getTimetableMON()
    {
        return $this->timetable_MON;
    }

    public function setTimetable_TUE(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_TUE = $timetable;
        return $this;
    }

    public function getTimetable_TUE()
    {
        return $this->timetable_TUE;
    }

    public function setTimetable_WED(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_WED = $timetable;
        return $this;
    }

    public function getTimetable_WED()
    {
        return $this->timetable_WED;
    }

    public function setTimetable_THU(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_THU = $timetable;
        return $this;
    }

    public function getTimetable_THU()
    {
        return $this->timetable_THU;
    }

    public function setTimetable_FRI(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_FRI = $timetable;
        return $this;
    }

    public function getTimetable_FRI()
    {
        return $this->timetable_FRI;
    }

    public function setTimetable_SAT(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_SAT = $timetable;
        return $this;
    }

    public function getTimetable_SAT()
    {
        return $this->timetable_SAT;
    }

    public function setTimetable_SUN(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable_SUN = $timetable;
        return $this;
    }

    public function getTimetable_SUN()
    {
        return $this->timetable_SUN;
    }

    public function __construct(\SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
		$this->setPlanificationPeriod($planificationPeriod);
    }
}

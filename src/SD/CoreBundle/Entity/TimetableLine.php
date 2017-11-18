<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\CoreBundle\Validator\TimetableLineBeginningTime;
use SD\CoreBundle\Validator\TimetableLineEndTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TimetableLine
 *
 * @ORM\Table(name="timetable_line")
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\TimetableLineRepository")
 * @ORM\HasLifecycleCallbacks()
 * @TimetableLineBeginningTime()
 * @TimetableLineEndTime()
 */
class TimetableLine
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Timetable")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timetable;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\Choice({"T", "D", "AM", "PM"})
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $beginningTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time")
     */
    private $endTime;

    /**
    * @ORM\Column(type="datetime", nullable=false)
    */
    private $createdAt;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $updatedAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TimetableLine
     */
    public function setType($type)
    {
		$this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set beginningTime
     *
     * @param \DateTime $beginningTime
     *
     * @return TimetableLine
     */
    public function setBeginningTime($beginningTime)
    {
        $this->beginningTime = $beginningTime;
        return $this;
    }

    /**
     * Get beginningTime
     *
     * @return \DateTime
     */
    public function getBeginningTime()
    {
        return $this->beginningTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return TimetableLine
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return TimetableLine
     */
    public function setUser(\SD\UserBundle\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set timetable
     *
     * @param \SD\CoreBundle\Entity\Timetable $timetable
     *
     * @return TimetableLine
     */
    public function setTimetable(\SD\CoreBundle\Entity\Timetable $timetable)
    {
        $this->timetable = $timetable;
        return $this;
    }

    /**
     * Get timetable
     *
     * @return \SD\UserBundle\Entity\Timetable
     */
    public function getTimetable()
    {
        return $this->timetable;
    }

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Timetable $timetable)
    {
    $this->setUser($user);
    $this->setTimetable($timetable);
    }

    /**
    * @ORM\PrePersist
    */
    public function createDate()
    {
        $this->createdAt = new \DateTime();
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function myToString()
    {
        return '*1*'.date_format($this->beginningTime, "H:i").'*2*'.$this->getId().'*3*'.number_format($this->id).'*4*'.$this->id.'*5*';
    }

    /**
    * @Assert\IsTrue(message="timetableLine.endTime.control")
    */
    public function isEndTime()
    {
	$interval = date_diff($this->getEndTime(), $this->getBeginningTime());
	return ($interval->format("%R") == "-");
    }
}

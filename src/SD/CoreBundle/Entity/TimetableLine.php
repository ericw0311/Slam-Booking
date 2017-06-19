<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SD\CoreBundle\Validator\TimetableLineOrder;

/**
 * TimetableLine
 *
 * @ORM\Table(name="timetable_line")
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\TimetableLineRepository")
 * @ORM\HasLifecycleCallbacks()
 * @TimetableLineOrder()
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\TimetableHeader")
     * @ORM\JoinColumn(nullable=false)
     */
    private $timetableHeader;

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
     * Set timetableHeader
     *
     * @param \SD\CoreBundle\Entity\TimetableHeader $timetableHeader
     *
     * @return TimetableLine
     */
    public function setTimetableHeader(\SD\CoreBundle\Entity\TimetableHeader $timetableHeader)
    {
        $this->timetableHeader = $timetableHeader;
        return $this;
    }

    /**
     * Get timetableHeader
     *
     * @return \SD\UserBundle\Entity\TimetableHeader
     */
    public function getTimetableHeader()
    {
        return $this->timetableHeader;
    }

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\TimetableHeader $timetableHeader)
    {
    $this->setUser($user);
    $this->setTimetableHeader($timetableHeader);
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
}

<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingLine
 *
 * @ORM\Table(name="booking_line", uniqueConstraints={@ORM\UniqueConstraint(name="uk_booking_line",columns={"resource_id", "ddate", "timetable_id", "timetable_line_id"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\BookingLineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BookingLine
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Booking")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="ddate", type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Planification")
     * @ORM\JoinColumn(nullable=false)
     */
	 private $planification;

    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\PlanificationPeriod")
     * @ORM\JoinColumn(nullable=false)
     */
	 private $planificationPeriod;

    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\PlanificationLine")
     * @ORM\JoinColumn(nullable=false)
     */
	 private $planificationLine;

	/**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Resource")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Timetable")
     * @ORM\JoinColumn(nullable=false)
     */
	 private $timetable;

    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\TimetableLine")
     * @ORM\JoinColumn(nullable=false)
     */
	 private $timetableLine;

	/**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */

    private $createdAt;

    /**
    * @ORM\Column(name="updated_at", type="datetime", nullable=true)
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
     * Set booking
     *
     * @param \SD\CoreBundle\Entity\Booking $booking
     *
     * @return BookingLine
     */
    public function setBooking(\SD\CoreBundle\Entity\Booking $booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * Get booking
     *
     * @return \SD\UserBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }
    
    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BookingLine
     */
    public function setDate($date)
    {
		$this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set planification
     *
     * @param \SD\CoreBundle\Entity\Planification $planification
     *
     * @return BookingLine
     */
    public function setPlanification(\SD\CoreBundle\Entity\Planification $planification)
    {
        $this->planification = $planification;
        return $this;
    }

    /**
     * Get planification
     *
     * @return \SD\UserBundle\Entity\Planification
     */
    public function getPlanification()
    {
        return $this->planification;
    }

    /**
     * Set planificationPeriod
     *
     * @param \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod
     *
     * @return BookingLine
     */
    public function setPlanificationPeriod(\SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod)
    {
        $this->planificationPeriod = $planificationPeriod;
        return $this;
    }

    /**
     * Get planificationPeriod
     *
     * @return \SD\UserBundle\Entity\PlanificationPeriod
     */
    public function getPlanificationPeriod()
    {
        return $this->planificationPeriod;
    }

    /**
     * Set planificationLine
     *
     * @param \SD\CoreBundle\Entity\PlanificationLine $planificationLine
     *
     * @return BookingLine
     */
    public function setPlanificationLine(\SD\CoreBundle\Entity\PlanificationLine $planificationLine)
    {
        $this->planificationLine = $planificationLine;
        return $this;
    }

    /**
     * Get planificationLine
     *
     * @return \SD\UserBundle\Entity\PlanificationLine
     */
    public function getPlanificationLine()
    {
        return $this->planificationLine;
    }

	/**
     * Set resource
     *
     * @param \SD\CoreBundle\Entity\Resource $resource
     *
     * @return BookingLine
     */
    public function setResource(\SD\CoreBundle\Entity\Resource $resource)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Get resource
     *
     * @return \SD\UserBundle\Entity\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set timetable
     *
     * @param \SD\CoreBundle\Entity\Timetable $timetable
     *
     * @return BookingLine
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

    /**
     * Set timetableLine
     *
     * @param \SD\CoreBundle\Entity\TimetableLine $timetableLine
     *
     * @return BookingLine
     */
    public function setTimetableLine(\SD\CoreBundle\Entity\TimetableLine $timetableLine)
    {
        $this->timetableLine = $timetableLine;
        return $this;
    }

    /**
     * Get timetableLine
     *
     * @return \SD\UserBundle\Entity\TimetableLine
     */
    public function getTimetableLine()
    {
        return $this->timetableLine;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return BookingLine
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

	public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Booking $booking)
    {
    $this->setUser($user);
    $this->setBooking($booking);
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


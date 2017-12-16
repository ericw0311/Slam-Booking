<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanificationLine
 *
 * @ORM\Table(name="planification_line", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification_line",columns={"planification_period_id", "week_day"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\PlanificationLineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PlanificationLine
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\PlanificationPeriod")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planificationPeriod;

    /**
     * @var string
     *
     * @ORM\Column(name="week_day", type="string", length=255)
     */
    private $weekDay;

     /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Timetable")
     * @ORM\JoinColumn(nullable=true)
     */
    private $timetable;

    /**
    * @ORM\Column(name="oorder", type="smallint", nullable=false)
    */
    private $order;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

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
     * Set planificationPeriod
     *
     * @param \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod
     *
     * @return PlanificationLine
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
     * Set weekDay
     *
     * @param string $weekDay
     *
     * @return PlanificationLine
     */
    public function setWeekDay($weekDay)
    {
        $this->weekDay = $weekDay;
        return $this;
    }

    /**
     * Get weekDay
     *
     * @return string
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Set timetable
     *
     * @param \SD\CoreBundle\Entity\Timetable $timetable
     *
     * @return PlanificationLine
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
     * Set order
     *
     * @param smallint $order
     *
     * @return PlanificationLine
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return smallint
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return PlanificationLine
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return PlanificationLine
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\PlanificationPeriod $planificationPeriod, $weekDay, $order)
    {
		$this->setUser($user);
		$this->setPlanificationPeriod($planificationPeriod);
		$this->setWeekDay($weekDay);
		$this->setOrder($order);
		$this->setActive(0);
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

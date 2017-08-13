<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanificationPeriod
 *
 * @ORM\Table(name="planification_period", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification_period",columns={"planification_id", "beginning_date"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\PlanificationPeriodRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PlanificationPeriod
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
     * @var \DateTime
     *
     * @ORM\Column(name="beginning_date", type="date", nullable=true)
     */
    private $beginningDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;


    /**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Planification")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planification;

    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
     * @ORM\OneToMany(targetEntity="PlanificationResource", mappedBy="planificationPeriod", cascade={"persist", "remove"})
     */
    private $planificationResources;

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
     * Set beginningDate
     *
     * @param \DateTime $beginningDate
     *
     * @return PlanificationPeriod
     */
    public function setBeginningDate($beginningDate)
    {
        $this->beginningDate = $beginningDate;
        return $this;
    }

    /**
     * Get beginningDate
     *
     * @return \DateTime
     */
    public function getBeginningDate()
    {
        return $this->beginningDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return PlanificationPeriod
     */
    public function setEndDate($endDate)
    {
		$this->endDate = $endDate;
		return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
		return $this->endDPlanificationPeriodate;
    }

    /**
     * Set planification
     *
     * @param \SD\CoreBundle\Entity\Planification $planification
     *
     * @return PlanificationPeriod
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return PlanificationPeriod
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Planification $planification)
    {
    $this->setUser($user);
    $this->setPlanification($planification);
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

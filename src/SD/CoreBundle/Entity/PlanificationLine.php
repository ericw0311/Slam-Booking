<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanificationLine
 *
 * @ORM\Table(name="planification_line", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification_line",columns={"planification_header_id", "beginning_date"})})
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\PlanificationHeader")
     * @ORM\JoinColumn(nullable=false)
     */
    private $planificationHeader;

    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
     * @ORM\OneToMany(targetEntity="PlanificationResource", mappedBy="planificationLine", cascade={"persist", "remove"})
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
     * @return PlanificationLine
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
     * @return PlanificationLine
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
		return $this->endDPlanificationLineate;
    }

    /**
     * Set planificationHeader
     *
     * @param \SD\CoreBundle\Entity\PlanificationHeader $planificationHeader
     *
     * @return PlanificationLine
     */
    public function setPlanificationHeader(\SD\CoreBundle\Entity\PlanificationHeader $planificationHeader)
    {
        $this->planificationHeader = $planificationHeader;
        return $this;
    }

    /**
     * Get planificationHeader
     *
     * @return \SD\UserBundle\Entity\PlanificationHeader
     */
    public function getPlanificationHeader()
    {
        return $this->planificationHeader;
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\PlanificationHeader $planificationHeader)
    {
    $this->setUser($user);
    $this->setPlanificationHeader($planificationHeader);
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

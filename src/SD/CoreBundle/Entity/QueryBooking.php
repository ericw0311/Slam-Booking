<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * QueryBooking
 *
 * @ORM\Table(name="query_booking", uniqueConstraints={@ORM\UniqueConstraint(name="uk_queryBooking",columns={"file_id", "name"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\QueryBookingRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "name"}, errorPath="name", message="dashboard.already.exists")
 */
class QueryBooking
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="period_type", type="string", length=255)
     */
    private $periodType;

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
     * @var string
     *
     * @ORM\Column(name="user_type", type="string", length=255)
     */
    private $userType;

    /**
     * @var string
     *
     * @ORM\Column(name="resource_type", type="string", length=255)
     */
    private $resourceType;


    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
    * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\File")
    * @ORM\JoinColumn(nullable=false)
    */
    private $file;

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
     * Set name
     *
     * @param string $name
     *
     * @return QueryBooking
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set periodType
     *
     * @param string $periodType
     *
     * @return QueryBooking
     */
    public function setPeriodType($periodType)
    {
        $this->periodType = $periodType;
        return $this;
    }

    /**
     * Get periodType
     *
     * @return string
     */
    public function getPeriodType()
    {
        return $this->periodType;
    }

    /**
     * Set beginningDate
     *
     * @param \DateTime $beginningDate
     *
     * @return QueryBooking
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
     * @return QueryBooking
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
        return $this->endDate;
    }

    /**
     * Set userType
     *
     * @param string $userType
     *
     * @return QueryBooking
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * Get userType
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set resourceType
     *
     * @param string $resourceType
     *
     * @return QueryBooking
     */
    public function setResourceType($resourceType)
    {
        $this->resourceType = $resourceType;
        return $this;
    }

    /**
     * Get resourceType
     *
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }
    
    
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Activity
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
     * Set file
     *
     * @param \SD\CoreBundle\Entity\File $file
     *
     * @return Activity
     */
    public function setFile(\SD\CoreBundle\Entity\File $file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return \SD\CoreBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
    $this->setUser($user);
    $this->setFile($file);
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


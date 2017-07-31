<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanificationResource
 *
 * @ORM\Table(name="planification_resource", uniqueConstraints={@ORM\UniqueConstraint(name="uk_planification_resource",columns={"planification_line_id", "resource_id"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\PlanificationResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PlanificationResource
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
     * Set planificationLine
     *
     * @param \SD\CoreBundle\Entity\PlanificationLine $planificationLine
     *
     * @return PlanificationResource
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
     * @return PlanificationResource
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return PlanificationResource
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\PlanificationLine $planificationLine, \SD\CoreBundle\Entity\Resource $resource)
    {
    $this->setUser($user);
    $this->setPlanificationLine($planificationLine);
    $this->setResource($resource);
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

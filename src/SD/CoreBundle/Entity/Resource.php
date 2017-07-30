<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Resource
 *
 * @ORM\Table(name="resource")
 * @ORM\Table(name="resource", uniqueConstraints={@ORM\UniqueConstraint(name="uk_resource",columns={"file_id", "type", "name"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\ResourceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "type", "name"}, errorPath="name", message="resource.already.exists")
 */
class Resource
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
     * @var bool
     *
     * @ORM\Column(name="internal", type="boolean")
     */
    private $internal;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
    * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\ResourceClassification")
    * @ORM\JoinColumn(nullable=true)
    */
    private $classification;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="background_color", type="string", length=255)
     */
    private $backgroundColor;

    /**
     * @var string
     *
     * @ORM\Column(name="foreground_color", type="string", length=255)
     */
    private $foregroundColor;

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
     * Set internal
     *
     * @param boolean $internal
     *
     * @return Resource
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
        return $this;
    }

    /**
     * Get internal
     *
     * @return bool
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Resource
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
     * Set code
     *
     * @param string $code
     *
     * @return Resource
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set classification
     *
     * @param \SD\CoreBundle\Entity\ResourceClassification $classification
     *
     * @return Resource
     */
    public function setClassification(\SD\CoreBundle\Entity\ResourceClassification $classification)
    {
        $this->classification = $classification;
        return $this;
    }

    /**
     * Get classification
     *
     * @return \SD\CoreBundle\Entity\ResourceClassification
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Resource
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
     * Set backgroundColor
     *
     * @param string $backgroundColor
     *
     * @return Resource
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    /**
     * Get backgroundColor
     *
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Set foregroundColor
     *
     * @param string $foregroundColor
     *
     * @return Resource
     */
    public function setForegroundColor($foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;
        return $this;
    }

    /**
     * Get foregroundColor
     *
     * @return string
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Resource
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
     * @return Resource
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

    public function get_tprr_createdAt()
    {
        return $this->createdAt;
    }

    public function get_tprr_updatedAt()
    {
        return $this->updatedAt;
    }
}

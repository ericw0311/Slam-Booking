<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Timetable
 *
 * @ORM\Table(name="timetable", uniqueConstraints={@ORM\UniqueConstraint(name="uk_timetable",columns={"file_id", "name"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\TimetableRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "name"}, errorPath="name", message="timetable.already.exists")
 */
class Timetable
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
    * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\File")
    * @ORM\JoinColumn(nullable=false)
    */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\Choice({"T", "D", "HD"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="TimetableLine", mappedBy="timetable", cascade={"persist", "remove"})
     */
    private $timetableLines;

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
     * @return Timetable
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
     * Set type
     *
     * @param string $type
     *
     * @return Timetable
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
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    private $createdAt;

    /**
    * @ORM\Column(name="updated_at", type="datetime", nullable=true)
    */
    private $updatedAt;

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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file)
    {
    $this->setUser($user);
    $this->setFile($file);
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Timetable
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
     * @return Timetable
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
}

<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * File
 *
 * @ORM\Table(name="file", uniqueConstraints={@ORM\UniqueConstraint(name="uk_file",columns={"user_id", "name"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"user", "name"}, errorPath="name", message="file.already.exists")
 */
class File
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
     * One File has Many UserFile.
     * @ORM\OneToMany(targetEntity="UserFile", mappedBy="file", cascade={"persist", "remove"})
     */
    private $userFiles;


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
     * @return File
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
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return File
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

    public function __construct(\SD\UserBundle\Entity\User $user)
    {
    $this->setUser($user);
    $this->userFiles = new ArrayCollection();
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

    /**
     * Add userFile
     *
     * @param \SD\CoreBundle\Entity\UserFile $userFile
     *
     * @return File
     */
    public function addUserFile(\SD\CoreBundle\Entity\UserFile $userFile)
    {
        $this->userFiles[] = $userFile;
        $userFile->setFile($this);
        return $this;
    }

    /**
     * Remove userFile
     *
     * @param \SD\CoreBundle\Entity\UserFile $userFile
     */
    public function removeUserFile(\SD\CoreBundle\Entity\UserFile $userFile)
    {
        $this->userFiles->removeElement($userFile);
    }

    /**
     * Get userFiles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserFiles()
    {
        return $this->userFiles;
    }
}


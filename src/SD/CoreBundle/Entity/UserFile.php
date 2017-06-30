<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserFile
 *
 * @ORM\Table(name="user_file")
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\UserFileRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"file", "email"}, errorPath="email", message="user.already.assigned.to.file")
 */
class UserFile
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="account_type", type="string", length=255)
     */
    private $accountType = "INDIVIDUAL";

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_name", type="string", length=255, nullable=true)
     */
    private $uniqueName;

    /**
     * @var bool
     *
     * @ORM\Column(name="administrator", type="boolean")
     */
    private $administrator;

    /**
     * @var bool
     *
     * @ORM\Column(name="user_created", type="boolean")
     */
    private $userCreated;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=true)
     */
    private $userName;

    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User", cascade="persist")
     * @ORM\JoinColumn(nullable=true)
     */
    private $account;

    /**
    * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\File", inversedBy="userFiles")
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
     * Set email
     *
     * @param string $email
     *
     * @return UserFile
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set accountType
     *
     * @param string $accountType
     *
     * @return UserFile
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * Get accountType
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return UserFile
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return UserFile
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getFirstAndLastName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * Set uniqueName
     *
     * @param string $uniqueName
     *
     * @return UserFile
     */
    public function setUniqueName($uniqueName)
    {
        $this->uniqueName = $uniqueName;
        return $this;
    }

    /**
     * Get uniqueName
     *
     * @return string
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    /**
     * Set administrator
     *
     * @param boolean $administrator
     *
     * @return UserFile
     */
    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
        return $this;
    }

    /**
     * Get administrator
     *
     * @return bool
     */
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Set userCreated
     *
     * @param boolean $userCreated
     *
     * @return UserFile
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    /**
     * Get userCreated
     *
     * @return boolean
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return UserFile
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return UserFile
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
     * @return UserFile
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

    /**
     * Set account
     *
     * @param \SD\UserBundle\Entity\User $account
     *
     * @return UserFile
     */
    public function setAccount(\SD\UserBundle\Entity\User $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getAccount()
    {
        return $this->account;
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


    /**
    * @Assert\IsTrue(message="user.organisation.name.null")
    */
    public function isUniqueName()
    {
        return ($this->getAccountType() != 'ORGANISATION' or $this->getUniqueName() !== null);
    }
}


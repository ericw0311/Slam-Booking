<?php
// src/SD/UserBundle/Entity/User.php

namespace SD\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sd_user")
 * @ORM\Entity(repositoryClass="SD\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
   protected $id;
  
  /**
  * @ORM\Column(type="string", length=255)
  *
  * @Assert\NotBlank(groups={"Registration", "Profile"})
  */
  protected $accountType = "INDIVIDUAL";

  /**
  * @ORM\Column(type="string", length=255)
  *
  * @Assert\NotBlank(groups={"Registration", "Profile"})
  */
  protected $lastName;  

  /**
  * @ORM\Column(type="string", length=255)
  *
  * @Assert\NotBlank(groups={"Registration", "Profile"})
  */
  protected $firstName;
  
  /**
  * @ORM\Column(type="string", length=255, nullable=true)
  */
  protected $uniqueName;
  
/**
  * Set lastName
  *
  * @param string lastName
  *
  * @return User
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
  * @param string firstName
  *
  * @return User
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

    /**
     * Set accountType
     *
     * @param string $accountType
     *
     * @return User
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
     * Set uniqueName
     *
     * @param string $uniqueName
     *
     * @return User
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
    * @Assert\IsTrue(message="user.organisation.name.null")
    */
    public function isUniqueName()
    {
        return ($this->getAccountType() != 'ORGANISATION' or $this->getUniqueName() !== null);
    }
}

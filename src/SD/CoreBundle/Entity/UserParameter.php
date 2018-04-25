<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserParameter
 *
 * @ORM\Table(name="user_parameter")
 * @ORM\Table(name="user_parameter", uniqueConstraints={@ORM\UniqueConstraint(name="uk_user_parameter",columns={"user_id", "parameterGroup", "parameter"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\UserParameterRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserParameter
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
     * @ORM\Column(name="parameterGroup", type="string", length=255)
     */
    private $parameterGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="parameter", type="string", length=255)
     */
    private $parameter;

    /**
     * @var string
     *
     * @ORM\Column(name="parameterType", type="string", length=255)
     */
    private $parameterType;

    /**
     * @var int
     *
     * @ORM\Column(name="integerValue", type="integer", nullable=true)
     */
    private $integerValue;

    /**
     * @var string
     *
     * @ORM\Column(name="stringValue", type="string", length=255, nullable=true)
     */
    private $stringValue;

    /**
     * @var bool
     *
     * @ORM\Column(name="booleanValue", type="boolean")
     */
    private $booleanValue;


    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
 
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
     * Set parameterGroup
     *
     * @param string $parameterGroup
     *
     * @return UserParameter
     */
    public function setParameterGroup($parameterGroup)
    {
        $this->parameterGroup = $parameterGroup;
        return $this;
    }

    /**
     * Get parameterGroup
     *
     * @return string
     */
    public function getParameterGroup()
    {
        return $this->parameterGroup;
    }

    /**
     * Set parameter
     *
     * @param string $parameter
     *
     * @return UserParameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
        return $this;
    }

    /**
     * Get parameter
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set parameterType
     *
     * @param string $parameterType
     *
     * @return UserParameter
     */
    public function setParameterType($parameterType)
    {
        $this->parameterType = $parameterType;
        return $this;
    }

    /**
     * Get parameterType
     *
     * @return string
     */
    public function getParameterType()
    {
        return $this->parameterType;
    }

    /**
     * Set integerValue
     *
     * @param integer $integerValue
     *
     * @return UserParameter
     */
    public function setIntegerValue($integerValue)
    {
        $this->integerValue = $integerValue;
        return $this;
    }

    /**
     * Get integerValue
     *
     * @return int
     */
    public function getIntegerValue()
    {
        return $this->integerValue;
    }

    /**
     * Set stringValue
     *
     * @param string $stringValue
     *
     * @return UserParameter
     */
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;
        return $this;
    }

    /**
     * Get stringValue
     *
     * @return string
     */
    public function getStringValue()
    {
        return $this->stringValue;
    }

    /**
     * Set booleanValue
     *
     * @param boolean $booleanValue
     *
     * @return UserParameter
     */
    public function setBooleanValue($booleanValue)
    {
        $this->booleanValue = $booleanValue;
        return $this;
    }

    /**
     * Get booleanValue
     *
     * @return bool
     */
    public function getBooleanValue()
    {
        return $this->booleanValue;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return UserParameter
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
    
    public function __construct(\SD\UserBundle\Entity\User $user, $parameterGroup, $parameter) {
        $this->user = $user;
        $this->parameterGroup = $parameterGroup;
        $this->parameter = $parameter;
        $this->setSDBooleanValue(false);
    }

    public function setSDIntegerValue($integerValue)
    {
        $this->setIntegerValue($integerValue);
        $this->setParameterType('integer');
        return $this;
    }

    public function setSDStringValue($stringValue)
    {
        $this->setStringValue($stringValue);
        $this->setParameterType('string');
        return $this;
    }

    public function setSDBooleanValue($booleanValue)
    {
        $this->setBooleanValue($booleanValue);
        $this->setParameterType('boolean');
        return $this;
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

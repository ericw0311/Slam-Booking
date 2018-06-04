<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Planification")
     * @ORM\JoinColumn(nullable=false)
     */
	private $planification;

	/**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Resource")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resource;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

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
	 * @ORM\OneToOne(targetEntity="SD\CoreBundle\Entity\Note")
	 * @ORM\JoinColumn(name="form_note_id", referencedColumnName="id")
     */
    private $formNote;

    /**
    * @ORM\Column(name="created_at", type="datetime", nullable=false)
    */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="BookingLine", mappedBy="booking", cascade={"persist", "remove"})
     */
    private $bookingLines;

    /**
     * @ORM\OneToMany(targetEntity="BookingUser", mappedBy="booking", cascade={"persist", "remove"})
     */
    private $bookingUserFiles;

    /**
     * @ORM\OneToMany(targetEntity="BookingLabel", mappedBy="booking", cascade={"persist", "remove"})
     */
    private $bookingLabels;

    /**
    * @ORM\Column(name="beginning_date", type="datetime", nullable=false)
    */
    private $beginningDate;

    /**
    * @ORM\Column(name="end_date", type="datetime", nullable=false)
    */
    private $endDate;

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
     * Set planification
     *
     * @param \SD\CoreBundle\Entity\Planification $planification
     *
     * @return Booking
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
     * Set resource
     *
     * @param \SD\CoreBundle\Entity\Resource $resource
     *
     * @return Booking
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
     * Set note
     *
     * @param string $note
     *
     * @return Booking
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Booking
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
     * @return Booking
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
     * Set beginningDate
     *
     * @param \DateTime $beginningDate
     *
     * @return Booking
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
     * @return Booking
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
     * Set formNote
     *
     * @param \SD\CoreBundle\Entity\Note $formNote
     *
     * @return Booking
     */
    public function setFormNote(\SD\CoreBundle\Entity\Note $formNote)
    {
		$this->formNote = $formNote;
		return $this;
    }

    /**
     * Get formNote
     *
     * @return \SD\CoreBundle\Entity\Note
     */
    public function getFormNote()
    {
		return $this->formNote;
    }

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\File $file, \SD\CoreBundle\Entity\Planification $planification, \SD\CoreBundle\Entity\Resource $resource)
    {
		$this->setUser($user);
		$this->setFile($file);
		$this->setPlanification($planification);
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
    
    
    public function get_tprr_createdAt()
    {
        return $this->createdAt;
    }

    public function get_tprr_updatedAt()
    {
        return $this->updatedAt;
    }
}

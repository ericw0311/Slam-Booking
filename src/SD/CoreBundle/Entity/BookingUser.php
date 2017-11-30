<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingUser
 *
 * @ORM\Table(name="booking_user", uniqueConstraints={@ORM\UniqueConstraint(name="uk_booking_user",columns={"booking_id", "user_file_id"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\BookingUserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BookingUser
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Booking")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;

	/**
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\UserFile")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userFile;

    /**
    * @ORM\Column(name="oorder", type="smallint", nullable=false)
    */
    private $order;

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
     * Set booking
     *
     * @param \SD\CoreBundle\Entity\Booking $booking
     *
     * @return BookingUser
     */
    public function setBooking(\SD\CoreBundle\Entity\Booking $booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * Get booking
     *
     * @return \SD\UserBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set userFile
     *
     * @param \SD\CoreBundle\Entity\UserFile $userFile
     *
     * @return BookingUser
     */
    public function setUserFile(\SD\CoreBundle\Entity\UserFile $userFile)
    {
        $this->userFile = $userFile;
        return $this;
    }

    /**
     * Get userFile
     *
     * @return \SD\UserBundle\Entity\UserFile
     */
    public function getUserFile()
    {
        return $this->userFile;
    }

    /**
     * Set order
     *
     * @param smallint $order
     *
     * @return BookingUser
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Get order
     *
     * @return smallint
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return BookingUser
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Booking $booking, \SD\CoreBundle\Entity\UserFile $userFile)
    {
    $this->setUser($user);
    $this->setBooking($booking);
    $this->setUserFile($userFile);
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

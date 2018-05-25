<?php

namespace SD\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BookingLabel
 *
 * @ORM\Table(name="booking_label", uniqueConstraints={@ORM\UniqueConstraint(name="uk_booking_label",columns={"booking_id", "label_id"})})
 * @ORM\Entity(repositoryClass="SD\CoreBundle\Repository\BookingLabelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BookingLabel
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
     * @ORM\ManyToOne(targetEntity="SD\CoreBundle\Entity\Label")
     * @ORM\JoinColumn(nullable=false)
     */
    private $label;

    /**
     * @var int
     *
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
     * @return BookingLabel
     */
    public function setBooking(\SD\CoreBundle\Entity\Booking $booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * Get booking
     *
     * @return \SD\CoreBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set label
     *
     * @param \SD\CoreBundle\Entity\Label $label
     *
     * @return BookingLabel
     */
    public function setLabel(\SD\CoreBundle\Entity\Label $label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get label
     *
     * @return \SD\CoreBundle\Entity\Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set order
     *
     * @param smallint $order
     *
     * @return BookingLabel
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
     * @return BookingLabel
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

    public function __construct(\SD\UserBundle\Entity\User $user, \SD\CoreBundle\Entity\Booking $booking, \SD\CoreBundle\Entity\Label $label)
    {
    $this->setUser($user);
    $this->setBooking($booking);
    $this->setLabel($label);
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


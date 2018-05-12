<?php
// src/CoreBundle/DataFixtures/ORM/LoadBookingUser_691.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\BookingUser;

class LoadBookingUser_691 extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12011'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12017'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12048'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12091'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12232'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12233'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12407'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12408'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12446'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-12933'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13097'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13098'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13285'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13287'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13288'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13712'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13713'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13714'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-13716'), $this->getReference('userFile-1681')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14816'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14817'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14822'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14823'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15010'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15011'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15012'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15013'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15014'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15015'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15016'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15017'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15018'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-16081'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-16244'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-16387'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-16389'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-16784'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-17013'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-17223'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-17311'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-17821'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18341'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-20803'), $this->getReference('userFile-1681')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-20846'), $this->getReference('userFile-1573')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22296'), $this->getReference('userFile-1575')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
    }

    public function getOrder()
    {
    return 58;
    }
}

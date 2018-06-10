<?php
// src/CoreBundle/DataFixtures/ORM/LoadBookingUser_707.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\BookingUser;

class LoadBookingUser_707 extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14185'), $this->getReference('userFile-1638')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14260'), $this->getReference('userFile-1702')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14309'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14310'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14311'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14312'), $this->getReference('userFile-1702')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14313'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14359'), $this->getReference('userFile-1702')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14361'), $this->getReference('userFile-1702')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14633'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14634'), $this->getReference('userFile-1720')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14696'), $this->getReference('userFile-1638')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14697'), $this->getReference('userFile-1638')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14698'), $this->getReference('userFile-1638')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14699'), $this->getReference('userFile-1638')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-14964'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15031'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15032'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15033'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15034'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15035'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15036'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15037'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15038'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15056'), $this->getReference('userFile-1717')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15057'), $this->getReference('userFile-1720')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15160'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15161'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15352'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-15353'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-17310'), $this->getReference('userFile-1720')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18070'), $this->getReference('userFile-1647')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18071'), $this->getReference('userFile-1647')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18481'), $this->getReference('userFile-1702')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18542'), $this->getReference('userFile-1735')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18543'), $this->getReference('userFile-1735')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18593'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18594'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18642'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18643'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-18644'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-20538'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-20539'), $this->getReference('userFile-1720')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-21611'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22820'), $this->getReference('userFile-1706')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22821'), $this->getReference('userFile-1706')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22961'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22962'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22963'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22964'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-22965'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-23355'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-23356'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
$bookingUser = new BookingUser($this->getReference('user-1'), $this->getReference('booking-23357'), $this->getReference('userFile-1635')); $bookingUser->setOrder(1); $manager->persist($bookingUser); $manager->flush();
    }

    public function getOrder()
    {
    return 61;
    }
}

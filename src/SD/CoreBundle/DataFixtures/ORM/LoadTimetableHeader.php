<?php
// src/CoreBundle/DataFixtures/ORM/LoadTimetableHeader.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\TimetableHeader;

class LoadTimetableHeader extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-321')); $timetableHeader->setName('Créneau Horaire'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-80', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-321')); $timetableHeader->setName('Grille horaire 2'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-81', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-440')); $timetableHeader->setName('Grille horaire'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-159', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-492')); $timetableHeader->setName('Grille horaire'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-196', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-507')); $timetableHeader->setName('Ecole de Tennis Lundi'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-211', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-507')); $timetableHeader->setName('Ecole de Tennis mercredi'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-210', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-507')); $timetableHeader->setName('Entraînements adultes jeudi'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-213', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-507')); $timetableHeader->setName('Entraînements adultes mercredi'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-212', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-507')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-208', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-516')); $timetableHeader->setName('Salle de conférences'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-220', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-516')); $timetableHeader->setName('salle informatique 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-218', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-516')); $timetableHeader->setName('Salle informatique 19'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-219', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-523')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-225', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-523')); $timetableHeader->setName('Grille horaire 2'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-226', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-523')); $timetableHeader->setName('Grille horaire 3'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-394', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-535')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-242', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-535')); $timetableHeader->setName('Grille SAMEDI'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-245', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-535')); $timetableHeader->setName('Grille VENDREDI'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-244', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-538')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-246', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-538')); $timetableHeader->setName('Grille horaire 2'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-247', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-550')); $timetableHeader->setName('Grille horaire Salle de Réunion'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-298', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-555')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-258', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-598')); $timetableHeader->setName('Grille journalière'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-283', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-612')); $timetableHeader->setName('Dimanche'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-304', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-612')); $timetableHeader->setName('DIMANCHE 2'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-364', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-612')); $timetableHeader->setName('Lun - Sam'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-349', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-612')); $timetableHeader->setName('mardi'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-355', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-612')); $timetableHeader->setName('Novembre'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-303', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-614')); $timetableHeader->setName('h'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-305', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-614')); $timetableHeader->setName('Projecteur'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-309', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-614')); $timetableHeader->setName('Visite'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-308', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-646')); $timetableHeader->setName('Grille horaire'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-340', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-690')); $timetableHeader->setName('Grille horaire KPC'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-365', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-691')); $timetableHeader->setName('Grille horaire Conf Call'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-366', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 1'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-375', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 2'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-376', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 3'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-377', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 4'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-380', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 5'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-381', $timetableHeader);
$timetableHeader = new TimetableHeader($this->getReference('user-1'), $this->getReference('file-707')); $timetableHeader->setName('Grille horaire 6'); $manager->persist($timetableHeader); $manager->flush(); $this->addReference('timetableHeader-382', $timetableHeader);
    }


    public function getOrder()
    {
    return 6;
    }
}

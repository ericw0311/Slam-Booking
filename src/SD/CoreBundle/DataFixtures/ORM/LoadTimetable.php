<?php
// src/CoreBundle/DataFixtures/ORM/LoadTimetable.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use SD\CoreBundle\Entity\Timetable;

class LoadTimetable extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-321')); $timetable->setName('Créneau Horaire'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-80', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-321')); $timetable->setName('Grille horaire 2'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-81', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-440')); $timetable->setName('Grille horaire'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-159', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-492')); $timetable->setName('Grille horaire'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-196', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Ecole de Tennis Lundi'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-211', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Ecole de Tennis mercredi'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-210', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Entraînements adultes jeudi'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-213', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Entraînements adultes mercredi'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-212', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-208', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-516')); $timetable->setName('Salle de conférences'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-220', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-516')); $timetable->setName('salle informatique 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-218', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-516')); $timetable->setName('Salle informatique 19'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-219', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-523')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-225', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-523')); $timetable->setName('Grille horaire 2'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-226', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-523')); $timetable->setName('Grille horaire 3'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-394', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-535')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-242', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-535')); $timetable->setName('Grille SAMEDI'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-245', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-535')); $timetable->setName('Grille VENDREDI'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-244', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-538')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-246', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-538')); $timetable->setName('Grille horaire 2'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-247', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-550')); $timetable->setName('Grille horaire Salle de Réunion'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-298', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-555')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-258', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-598')); $timetable->setName('Grille journalière'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-283', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('Dimanche'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-304', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('DIMANCHE 2'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-364', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('Lun - Sam'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-349', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('mardi'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-355', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('Novembre'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-303', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-614')); $timetable->setName('h'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-305', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-614')); $timetable->setName('Projecteur'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-309', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-614')); $timetable->setName('Visite'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-308', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-646')); $timetable->setName('Grille horaire'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-340', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-690')); $timetable->setName('Grille horaire KPC'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-365', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-691')); $timetable->setName('Grille horaire Conf Call'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-366', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 1'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-375', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 2'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-376', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 3'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-377', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 4'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-380', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 5'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-381', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Grille horaire 6'); $timetable->setType('T'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-382', $timetable);
    }


    public function getOrder()
    {
    return 6;
    }
}

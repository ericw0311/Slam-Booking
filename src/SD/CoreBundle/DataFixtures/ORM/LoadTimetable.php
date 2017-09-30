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

// Journées
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-321')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-321', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-440')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-440', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-492')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-492', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-507', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-516')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-516', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-523')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-523', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-535')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-535', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-538')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-538', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-550')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-550', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-555')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-555', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-598')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-598', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-612', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-614')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-614', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-646')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-646', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-648')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-648', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-690')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-690', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-691')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-691', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Journée'); $timetable->setType('D'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-D-707', $timetable);

// Demi journées
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-321')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-321', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-440')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-440', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-492')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-492', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-507')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-507', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-516')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-516', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-523')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-523', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-535')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-535', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-538')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-538', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-550')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-550', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-555')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-555', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-598')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-598', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-612')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-612', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-614')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-614', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-646')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-646', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-648')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-648', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-690')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-690', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-691')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-691', $timetable);
$timetable = new Timetable($this->getReference('user-1'), $this->getReference('file-707')); $timetable->setName('Demi-journée'); $timetable->setType('HD'); $manager->persist($timetable); $manager->flush(); $this->addReference('timetable-HD-707', $timetable);
    }


    public function getOrder()
    {
    return 6;
    }
}
